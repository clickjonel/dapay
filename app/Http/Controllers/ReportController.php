<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\OrganizationalIndicators;
use App\Models\ProgramIndicators;
use App\Models\Report;
use App\Models\ReportValueDisaggregation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
         // Validate the request
        $validated = $request->validate([
            'date' => 'required|date',
            'barangay_id' => 'required|exists:barangays,id',
            'total_clients' => 'required|integer|min:1',
            'total_returning_clients' => 'nullable|integer|min:0',
            'values' => 'required|array|min:1',
            'values.*.program_indicator_id' => 'required|exists:program_indicators,id',
            'values.*.sub_program_id' => 'nullable|exists:sub_programs,id',
            'values.*.total_value' => 'required|integer|min:0',
            'values.*.disaggregations' => 'required|array',
            'values.*.disaggregations.*.disaggregation_id' => 'required|exists:disaggregations,id',
            'values.*.disaggregations.*.value' => 'required|integer|min:0',
        ]);

       try {
            // Use database transaction to ensure data integrity
            DB::beginTransaction();

            // Create the main report
            $report = Report::create([
                'submitted_by' => Auth::id(),
                'total_clients' => $validated['total_clients'],
                'total_returning_clients' => $validated['total_returning_clients'] ?? 0,
                'barangay_id' => $validated['barangay_id'],
                'date' => $validated['date']
            ]);

            // Process each indicator's values
            foreach ($validated['values'] as $valueData) {
                // Create the report value record (one per indicator)
                $reportValue = $report->values()->create([
                    'sub_program_id' => $valueData['sub_program_id'] ?? null,
                    'program_indicator_id' => $valueData['program_indicator_id'],
                    'total_value' => $valueData['total_value'],
                ]);

                // Create disaggregation records for this report value
                $disaggregations = [];
                foreach ($valueData['disaggregations'] as $disaggregation) {
                    $disaggregations[] = [
                        'report_value_id' => $reportValue->id,
                        'disaggregation_id' => $disaggregation['disaggregation_id'],
                        'value' => $disaggregation['value'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert disaggregations for better performance
                if (!empty($disaggregations)) {
                    ReportValueDisaggregation::insert($disaggregations);
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()
                ->route('report.index')
                ->with('success', 'Report created successfully.');

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            // Log the error for debugging
            // Log::error('Report creation failed: ' . $e->getMessage(), [
            //     'user_id' => Auth::id(),
            //     'request_data' => $request->all(),
            //     'exception' => $e
            // ]);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create report. Please try again.']);
        }

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
        $report->load([
            'values.disaggregations',
            'barangay.municipality'
        ]);

        // Get active indicators with disaggregations
        $program_indicators = ProgramIndicators::with('disaggregations')
        ->where('active', true)
        ->has('disaggregations')
        ->get()
        ->map(function ($indicator) use ($report) {

            $indicator->disaggregations = $indicator->disaggregations->map(
                function ($disagg) use ($report, $indicator) {

                    $existingValue = $report->values
                        ->where('program_indicator_id', $indicator->id)
                        ->flatMap->disaggregations
                        ->firstWhere('disaggregation_id', $disagg->id);

                    $disagg->value = $existingValue?->value ?? 0;

                    return $disagg;
                }
            );

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
    // Validate the incoming request
    $validated = $request->validate([
        'date' => 'required|date',
        'barangay_id' => 'required|exists:barangays,id',
        'total_clients' => 'required|integer|min:1',
        'total_returning_clients' => 'nullable|integer|min:0',
        'values' => 'required|array|min:1',
        'values.*.program_indicator_id' => 'required|exists:program_indicators,id',
        'values.*.sub_program_id' => 'nullable|exists:sub_programs,id',
        'values.*.total_value' => 'required|integer|min:0',
        'values.*.disaggregations' => 'required|array',
        'values.*.disaggregations.*.disaggregation_id' => 'required|exists:disaggregations,id',
        'values.*.disaggregations.*.value' => 'required|integer|min:0',
    ]);

    try {
        // Use database transaction to ensure data integrity
        DB::beginTransaction();

        // Update the main report
        $report->update([
            'total_clients' => $validated['total_clients'],
            'total_returning_clients' => $validated['total_returning_clients'] ?? 0,
            'barangay_id' => $validated['barangay_id'],
            'date' => $validated['date']
        ]);

        // Delete all existing report values and their disaggregations
        // This is simpler than trying to update/delete/create individually
        foreach ($report->values as $value) {
            $value->disaggregations()->delete();
        }
        $report->values()->delete();

        // Create new report values and disaggregations
        foreach ($validated['values'] as $valueData) {
            // Create the report value record (one per indicator)
            $reportValue = $report->values()->create([
                'sub_program_id' => $valueData['sub_program_id'] ?? null,
                'program_indicator_id' => $valueData['program_indicator_id'],
                'total_value' => $valueData['total_value'],
            ]);

            // Create disaggregation records for this report value
            $disaggregations = [];
            foreach ($valueData['disaggregations'] as $disaggregation) {
                $disaggregations[] = [
                    'report_value_id' => $reportValue->id,
                    'disaggregation_id' => $disaggregation['disaggregation_id'],
                    'value' => $disaggregation['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert disaggregations for better performance
            if (!empty($disaggregations)) {
                ReportValueDisaggregation::insert($disaggregations);
            }
        }

        // Commit the transaction
        DB::commit();

        return redirect()
            ->route('report.index')
            ->with('success', 'Report updated successfully.');

    } catch (\Exception $e) {
        // Rollback transaction on error
        DB::rollBack();
        
        // Log the error for debugging
        // Log::error('Report update failed: ' . $e->getMessage(), [
        //     'report_id' => $report->id,
        //     'user_id' => Auth::id(),
        //     'request_data' => $request->all(),
        //     'exception' => $e
        // ]);

        return redirect()
            ->back()
            ->withInput()
            ->withErrors(['error' => 'Failed to update report. Please try again.']);
    }
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

    public function renderMonthlyUserReportPage()
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
           
        
        return Inertia::render('report/monthly-print', [
            'reports' => $reports,
        ]); 
    }


}
