<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\OrganizationalIndicators;
use App\Models\ProgramIndicators;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReportController extends Controller
{
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
        ->orderBy('id', 'desc')
        ->simplePaginate(50)
        ->withQueryString();

        return Inertia::render('report/index', [
            'reports' => $reports,
            'filters' => $request->only(['search', 'date'])
        ]);
    }

    public function showCreateReportPage()
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

    public function createReport(Request $request)
    {
        $report = Report::create([
            'submitted_by' => Auth::id(),
            'total_clients' => $request->input('total_clients'),
            'total_returning_clients' => $request->input('total_returning_clients'),
            'barangay_id' => $request->input('barangay_id'),
            'date' => $request->input('date')
        ]);

        $report->values()->createMany($request->input('values'));

        return redirect()->route('reports.index')->with('success', 'Report created successfully.');

    }
}
