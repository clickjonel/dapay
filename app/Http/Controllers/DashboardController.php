<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Province;
use App\Models\Report;
use App\Models\ReportValue;
use App\Models\SubProgram;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function PHPSTORM_META\map;

class DashboardController extends Controller
{
    public function geographicDashboard()
    {
        $provinces = Province::with(['municipalities', 'barangays'])->get();

        return Inertia::render('geographicDashboard', [
            'provinces' => $provinces,
        ]);
    }

    public function programIndicatorsDashboard()
    {
       $programs = SubProgram::with([
            'reportValues.disaggregations.disaggregation',
            'reportValues.report.barangay.province',
            'reportValues.programIndicator',
        ])
        ->get()
        ->map(function ($sub_program) {

            // Overall total per sub-program
            $sub_program->total_value = $sub_program->reportValues->sum('total_value');

            // Group report values by indicators, then by province
            $sub_program->indicators = $sub_program->reportValues
                ->groupBy(fn($rv) => $rv->programIndicator?->name ?? 'Undefined Indicator')
                ->map(function ($reportValues, $indicatorName) {
                    
                    // Total for this indicator across all provinces
                    $indicatorTotal = $reportValues->sum('total_value');
                    
                    // Group by province within this indicator
                    $provinces = $reportValues
                        ->groupBy(fn($rv) => $rv->report?->barangay?->province?->name ?? 'Undefined Province')
                        ->map(function ($provinceReportValues, $provinceName) {
                            
                            // Aggregate disaggregations for this province
                            $disaggregations = $provinceReportValues
                                ->flatMap(fn($rv) => $rv->disaggregations)
                                ->groupBy(fn($d) => $d->disaggregation?->name ?? 'Undefined')
                                ->map(function ($items, $disaggName) {
                                    $firstItem = $items->first();
                                    return [
                                        'name' => $disaggName,
                                        'value' => $items->sum('value'),
                                        'totalable' => $firstItem->disaggregation?->totalable ?? false,
                                    ];
                                })
                                ->values();
                            
                            return [
                                'province_name' => $provinceName,
                                'total_value' => $provinceReportValues->sum('total_value'),
                                'count' => $provinceReportValues->count(),
                                'disaggregations' => $disaggregations,
                            ];
                        })
                        ->values();
                    
                    return [
                        'indicator_name' => $indicatorName,
                        'total_value' => $indicatorTotal,
                        'provinces' => $provinces,
                    ];
                })
                ->values();

            unset($sub_program->reportValues);
            return $sub_program;
        });

        return Inertia::render('programIndicatorsDashboard', [
            'programs' => $programs,
        ]);
    }

}
