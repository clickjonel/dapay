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
            'programIndicator.disaggregations',
            'report.barangay.province',
            'disaggregation'
        ])
        ->get()
        // Group all report values by their sub-program name
        ->groupBy(fn($v) => $v->subProgram->name ?? 'Undefined Program')
        ->map(function($programGroup) {
            return [
                'indicators' => $programGroup
                    // Group each program's values by indicator name
                    ->groupBy(fn($v) => $v->programIndicator->name ?? 'No Indicator')
                    ->map(function($indicatorGroup) {
                        // Get the indicator model from the first item (all items share same indicator)
                        $indicator = $indicatorGroup->first()->programIndicator;
                        
                        // Build disaggregation metadata map (id => [name, totalable]) for quick lookup
                        // Cache this once to avoid repeated relationship queries
                        $disaggMeta = $indicator?->disaggregations->mapWithKeys(fn($d) => [
                            $d->id => [
                                'name' => $d->name,
                                'totalable' => (bool)$d->pivot->totalable, // Whether this disagg counts toward totals
                            ]
                        ]) ?? collect();
                        
                        // Check if this indicator has any disaggregations configured
                        $hasDisaggregations = $disaggMeta->isNotEmpty();

                        // Group indicator values by province, then by disaggregation within each province
                        $provinces = $indicatorGroup
                            ->groupBy(fn($v) => $v->report->barangay->province->name ?? 'No Province')
                            ->map(function($provinceGroup) use ($disaggMeta, $hasDisaggregations) {
                                // Track totalable sum inline to avoid filtering collection twice
                                $totalableSum = 0;
                                
                                // If no disaggregations configured, sum all values
                                if (!$hasDisaggregations) {
                                    $totalableSum = $provinceGroup->sum('value');
                                    
                                    return [
                                        'disaggregations' => collect([
                                            [
                                                'name' => 'Total',
                                                'raw' => $totalableSum,
                                                'value' => number_format($totalableSum),
                                                'totalable' => true,
                                            ]
                                        ]),
                                        'total' => number_format($totalableSum),
                                        'raw_total' => $totalableSum,
                                    ];
                                }
                                
                                // Group by disaggregation ID and calculate sums
                                $disaggTotals = $provinceGroup
                                    ->groupBy('disaggregation_id') // Direct property access is faster
                                    ->map(function($items, $disaggId) use ($disaggMeta, &$totalableSum) {
                                        // Sum all values for this disaggregation
                                        $sum = $items->sum('value');
                                        
                                        // Get metadata with safe default
                                        $meta = $disaggMeta->get($disaggId, ['name' => 'Not Identified', 'totalable' => false]);
                                        
                                        // Accumulate totalable values inline (avoids second iteration)
                                        if ($meta['totalable']) {
                                            $totalableSum += $sum;
                                        }
                                        
                                        return [
                                            'name' => $meta['name'],
                                            'raw' => $sum,                    // Numeric for calculations
                                            'value' => number_format($sum),   // Formatted for display
                                            'totalable' => $meta['totalable'], // Flag for UI styling
                                        ];
                                    })
                                    ->values(); // Re-index for clean JSON output

                                return [
                                    'disaggregations' => $disaggTotals,
                                    'total' => number_format($totalableSum),      // Province total (only totalable)
                                    'raw_total' => $totalableSum,                 // Unformatted for grand total calc
                                ];
                            });

                        // Calculate indicator's grand total across all provinces
                        return [
                            'disaggregations' => $disaggMeta->values(),    // Metadata for UI reference
                            'provinces' => $provinces,                      // All province data
                            'total' => number_format($provinces->sum('raw_total')),  // Grand total formatted
                            'raw_total' => $provinces->sum('raw_total'),              // Grand total raw
                        ];
                    }),
            ];
        });

        return Inertia::render('programIndicatorsDashboard', [
            'programs' => $programs,
        ]);
    }

}
