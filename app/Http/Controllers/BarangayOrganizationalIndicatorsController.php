<?php

namespace App\Http\Controllers;

use App\Models\BarangayOrganizationalIndicators;
use Illuminate\Http\Request;

class BarangayOrganizationalIndicatorsController extends Controller
{
    public function createBarangayOrgIndicators(Request $request)
    {
        $validated = $request->validate([
            'barangay_id' => 'required|numeric|exists:barangays,id',
            'indicators' => 'required|array',
            'indicators.*.organizational_indicator_id' => 'required|numeric|exists:organizational_indicators,id',
            'indicators.*.value' => 'required|numeric',
            'indicators.*.remarks' => 'nullable|string',
        ]);

        foreach ($validated['indicators'] as $indicator) {
            BarangayOrganizationalIndicators::updateOrCreate(
                [
                    'barangay_id' => $validated['barangay_id'],
                    'organizational_indicator_id' => $indicator['organizational_indicator_id'],
                ],
                [
                    'value' => $indicator['value'] ?? null,
                    'remarks' => $indicator['remarks'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Barangay Organizational Indicators updated successfully');
    }
}
