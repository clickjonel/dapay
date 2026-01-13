<?php

namespace App\Http\Controllers;

use App\Http\Requests\Program\StoreProgramRequest;
use App\Models\Program;
use App\Models\SubProgram;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('program/index',[
            'programs' => Program::with([
                'subPrograms'
            ])->latest('id')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('program/create',[]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProgramRequest $request)
    {
        $validated = $request->validated();

        $program = Program::create([
            'name' => $validated['name'],
            'active'=>true
        ]);

        if ($validated['sub_programs']) {
            foreach ($request->sub_programs as $subProgram) {
                SubProgram::create([
                    'name' => $subProgram['name'],
                    'active'=>true,
                    'program_id' => $program->id
                ]);
            }
        }
        else{
           SubProgram::create([
                'name' => $program->name,
                'active'=>true,
                'program_id' => $program->id
           ]); 
        }

        return redirect()->route('program.index')
            ->with('success', 'Program created successfully');
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
    public function edit(string $id)
    {
        return Inertia::render('program/edit',[
            'program' => Program::with(['subPrograms'])->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
         $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sub_programs' => 'nullable|array',
            'sub_programs.*.id' => 'nullable|exists:sub_programs,id',
            'sub_programs.*.name' => 'required|string|max:255'
        ]);

        $program->update([
            'name' => $validated['name']
        ]);

        // Get existing sub program IDs
        $existingIds = $program->subPrograms->pluck('id')->toArray();
        $submittedIds = collect($validated['sub_programs'] ?? [])
            ->pluck('id')
            ->filter()
            ->toArray();

        // Delete removed sub programs
        $toDelete = array_diff($existingIds, $submittedIds);
        if (!empty($toDelete)) {
            $program->subPrograms()->whereIn('id', $toDelete)->update(['active'=>false]);
        }

        // Update or create sub programs
        if (isset($validated['sub_programs'])) {
            foreach ($validated['sub_programs'] as $subProgram) {
                if (isset($subProgram['id'])) {
                    // Update existing
                    $program->subPrograms()
                        ->where('id', $subProgram['id'])
                        ->update(['name' => $subProgram['name']]);
                } else {
                    // Create new
                    $program->subPrograms()->create([
                        'name' => $subProgram['name'],
                        'active' => true
                    ]);
                }
            }
        }

        return redirect()->route('program.index')
            ->with('success', 'Program updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = Program::with(['subPrograms'])->find($id);
        $program->update(['active' => !$program->active]);
        foreach($program['subPrograms'] as $sub){
            $sub->update(['active' => !$sub->active]);
        }

    }
}
