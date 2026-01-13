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
        $programs = ReportValue::with([
                        'subProgram',
                        'programIndicator',
                        'report.barangay.province'
                    ])
                    ->get()
                    ->groupBy(fn($value) => $value->subProgram->name ?? 'Uncategorized')
                    ->map(function($subProgramItems) {

                        return $subProgramItems
                            ->groupBy(fn($item) => optional($item->programIndicator)->name ?? 'Undefined Indicator')
                            ->map(function($indicatorItems) {

                                $provinceGroups = $indicatorItems
                                    ->groupBy(fn($item) => optional($item->report->barangay->province)->name ?? 'No Province')
                                    ->map(fn($provinceItems) => number_format($provinceItems->sum('value')));
                                
                                $total = $indicatorItems->sum('value');
                                
                                return $provinceGroups->put('total', number_format($total));
                            });
                    });

        return Inertia::render('programIndicatorsDashboard', [
            'programs' => $programs,
        ]);
    }
}
