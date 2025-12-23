<?php

namespace App\Http\Controllers;

use App\Models\Disaggregation;
use App\Models\OrganizationalIndicators;
use App\Models\ProgramIndicatorDisaggregation;
use App\Models\ProgramIndicators;
use App\Models\SubProgram;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndicatorController extends Controller
{
    public function organizationalIndicatorsPage()
    {
        $org_indicators = OrganizationalIndicators::get();

        return Inertia::render('indicators/organizationalIndicators',[
            'org_indicators'=>$org_indicators,
        ]);
    }

    public function programIndicatorsPage()
    {
        $program_indicators = ProgramIndicators::with(['subProgram','disaggregations'])->get();
        $disaggregations = Disaggregation::where('active',true)->get()->groupBy('type');
        $sub_programs = SubProgram::get();

        return Inertia::render('indicators/programIndicators',[
            'program_indicators'=>$program_indicators,
            'disaggregations' => $disaggregations,
            'sub_programs' => $sub_programs
        ]);
    }

    public function createOrgIndicator(Request $request)
    {
           $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        OrganizationalIndicators::create([
            'name' => $validated['name'],
            'active' => true
        ]);
        
        return back()->with('success', 'Indicator created successfully');

    }

    public function createProgramIndicator(Request $request)
    {
           $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sub_program_id' => 'required|numeric|exists:sub_programs,id'
        ]);

        ProgramIndicators::create([
            'name' => $validated['name'],
            'sub_program_id' => $validated['sub_program_id'],
            'active' => true
        ]);
        
        return back()->with('success', 'Indicator created successfully');

    }

    public function updateOrgIndicator(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:organizational_indicators,name,' . $id
        ]);

        $indicator = OrganizationalIndicators::findOrFail($id);
        $indicator->update([
            'name' => $request->name
        ]);
        
        return back()->with('success', 'Indicator updated successfully');
    }

    public function updateProgramIndicator(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:organizational_indicators,name,' . $id,
            'sub_program_id' => 'required|numeric|exists:sub_programs,id'
        ]);

        $indicator = ProgramIndicators::findOrFail($id);
        $indicator->update([
            'name' => $request->name,
            'sub_program_id' => $request->sub_program_id
        ]);
        
        return back()->with('success', 'Indicator updated successfully');
    }

    public function disableOrgIndicator($id)
    {
        $indicator = OrganizationalIndicators::findOrFail($id);
        $indicator->update(['active' => !$indicator->active]);
        
        return back()->with('success', 'Indicator status updated');
    }

    public function disableProgramIndicator($id)
    {
        $indicator = ProgramIndicators::findOrFail($id);
        $indicator->update(['active' => !$indicator->active]);
        
        return back()->with('success', 'Indicator status updated');
    }

    public function setProgramIndicatorDisaggregations(Request $request, $id){
        $validated = $request->validate([
            'disaggregations' => 'nullable|array',
        ]);

       $indicator = ProgramIndicators::findOrFail($id);

        // If empty or not sent, this will DETACH all
        $indicator->disaggregations()->sync(
            $validated['disaggregations'] ?? []
        );

        return back()->with('success', 'Indicator disaggregations Synced');
    
    }

}
