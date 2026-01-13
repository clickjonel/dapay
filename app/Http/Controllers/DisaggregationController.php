<?php

namespace App\Http\Controllers;

use App\Models\Disaggregation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DisaggregationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $disaggregations = Disaggregation::get();

        return Inertia::render('disaggregation/index',[
            'disaggregations' => $disaggregations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:255'
            ]);

            Disaggregation::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'active' => true
            ]);

            return back()->with('success', 'Disaggregation created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Disaggregation $disaggregation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Disaggregation $disaggregation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Disaggregation $disaggregation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255'
        ]);

        $disaggregation = Disaggregation::findOrFail($disaggregation->id);
        $disaggregation->update($validated);

        return back()->with('success', 'Disaggregation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Disaggregation $disaggregation)
    {
        Disaggregation::find($disaggregation->id)->update(['active' => !$disaggregation->active]);

        return back()->with('success', 'Status updated successfully');
    }
}
