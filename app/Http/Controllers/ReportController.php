<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\OrganizationalIndicators;
use App\Models\ProgramIndicators;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reports = Report::with(['barangay.municipality', 'user'])
        ->when($request->input('search'), function ($query, $search) {
            $query->whereHas('barangay', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        })
        ->when($request->input('date'), function ($query, $date) {
            $query->whereDate('date', $date);
        })
        ->when($request->user()->user_level === 5, function ($query) use ($request) {
            $query->where('submitted_by', $request->user()->id);
        })
        // ->when($request->user()->user_level === 3, function ($query) use ($request) {
        //     $query->where('submitted_by', $request->user()->id);
        // })
        ->orderBy('id', 'desc')
        ->simplePaginate(50)
        ->withQueryString();

        return Inertia::render('report/index', [
            'reports' => $reports,
            'filters' => $request->only(['search', 'date'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $program_indicators = ProgramIndicators::with(['disaggregations'])->where('active', true)->has('disaggregations')->get();
        $org_indicators = OrganizationalIndicators::where('active', true)->get();
        $barangays = Barangay::get();

        return Inertia::render('report/create', [
            'program_indicators' => $program_indicators,
            'org_indicators' => $org_indicators,
            'barangays' => $barangays
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $report = Report::create([
            'submitted_by' => Auth::id(),
            'total_clients' => $request->input('total_clients'),
            'total_returning_clients' => $request->input('total_returning_clients'),
            'barangay_id' => $request->input('barangay_id'),
            'date' => $request->input('date')
        ]);

        $report->values()->createMany($request->input('values'));

        return redirect()->route('report.index')->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        // Load report with its values
        $report->load(['values', 'barangay.municipality']);

        // Get active indicators with disaggregations
        $program_indicators = ProgramIndicators::with(['disaggregations'])
            ->where('active', true)
            ->has('disaggregations')
            ->get()
            ->map(function ($indicator) use ($report) {
                // Map disaggregations with existing values
                $indicator->disaggregations = $indicator->disaggregations->map(function ($disagg) use ($report, $indicator) {
                    // Find existing value for this disaggregation
                    $existingValue = $report->values->filter(function ($value) use ($disagg, $indicator) {
                        return $value->disaggregation_id === $disagg->id 
                            && $value->program_indicator_id === $indicator->id;
                    })->first();
                    
                    // Add the value to the disaggregation
                    $disagg->value = $existingValue ? $existingValue->value : 0;
                    
                    return $disagg;
                });
                
                return $indicator;
            });

        $org_indicators = OrganizationalIndicators::where('active', true)->get();
        $barangays = Barangay::get();

        return Inertia::render('report/edit', [
            'report' => $report,
            'program_indicators' => $program_indicators,
            'org_indicators' => $org_indicators,
            'barangays' => $barangays
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'barangay_id' => ['required', 'exists:barangays,id'],
            'total_returning_clients' => ['nullable', 'integer', 'min:0'],
            'total_clients' => ['required', 'integer', 'min:1'],
            'values' => ['required', 'array'],
            'values.*.sub_program_id' => ['nullable', 'exists:sub_programs,id'],
            'values.*.program_indicator_id' => ['nullable', 'exists:program_indicators,id'],
            'values.*.organizational_indicator_id' => ['nullable', 'exists:organizational_indicators,id'],
            'values.*.disaggregation_id' => ['required', 'exists:disaggregations,id'],
            'values.*.indicator_type' => ['required', 'in:program,organizational'],
            'values.*.value' => ['required', 'integer', 'min:0'],
        ]);

        // Update report basic information
        $report->update([
            'date' => $validated['date'],
            'barangay_id' => $validated['barangay_id'],
            'total_returning_clients' => $validated['total_returning_clients'],
            'total_clients' => $validated['total_clients'],
        ]);

        // Delete existing values
        $report->values()->delete();

        // Create new values
        foreach ($validated['values'] as $valueData) {
            $report->values()->create($valueData);
        }

        return redirect()->route('report.index')
            ->with('success', 'Report updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function generateUserMonthlyReport()
    {
        $user = Auth::user();
        $reports = Report::with(['barangay.municipality', 'values.programIndicator'])
            ->where('submitted_by', $user->id)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->get();
            // ->map(function($report){`
                
            //     //group by sub program
            //     $report->programs = $report->values->groupBy(fn($values) => $values->subProgram->name ?? 'No Program Tagged');
                
            //     //group sub programs by indicator
                
            //     return $report;
            // });
           
        
        return Inertia::render('report/monthly', [
            'reports' => $reports,
        ]); 

        // return $reports;
    }


}
