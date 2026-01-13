<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\SubProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_program()
    {
        $response = $this->post('/program', [
            'name' => 'Test Program',
            'active' => true,
            'sub_programs' => [
                [
                    'name' => 'Sub Program 1',
                    'active' => true
                ],
                [
                    'name' => 'Sub Program 2',
                    'active' => false
                ]
            ]
        ]);

        //$response->assertStatus(302);
        $response->assertSessionHasNoErrors();
    
        $response->assertStatus(302);
        
        // $this->assertDatabaseHas('programs', [
        //     'name' => 'Test Program',
        //     'active' => true
        // ]);
    }

    /** @test */
    // public function it_creates_default_sub_program_when_none_provided()
    // {
    //     $response = $this->post('/program', [
    //         'name' => 'Test Program'
    //     ]);

    //     $program = Program::where('name', 'Test Program')->first();
    //     $this->assertDatabaseHas('sub_programs', [
    //         'name' => 'Test Program',
    //         'program_id' => $program->id
    //     ]);
    // }

    /** @test */
    // public function it_can_update_a_program()
    // {
    //     $program = Program::create(['name' => 'Original', 'active' => true]);
    //     $subProgram = SubProgram::create([
    //         'name' => 'Original Sub',
    //         'program_id' => $program->id,
    //         'active' => true
    //     ]);

    //     $response = $this->put("/program/{$program->id}", [
    //         'name' => 'Updated Program',
    //         'sub_programs' => [
    //             ['id' => $subProgram->id, 'name' => 'Updated Sub'],
    //             ['name' => 'New Sub']
    //         ]
    //     ]);

    //     $response->assertRedirect(route('program.index'));
    //     $this->assertDatabaseHas('programs', ['name' => 'Updated Program']);
    //     $this->assertDatabaseHas('sub_programs', ['name' => 'Updated Sub']);
    //     $this->assertDatabaseHas('sub_programs', ['name' => 'New Sub']);
    // }

    /** @test */
    // public function it_can_toggle_program_status()
    // {
    //     $program = Program::create(['name' => 'Test', 'active' => true]);
    //     $subProgram = SubProgram::create([
    //         'name' => 'Sub Test',
    //         'program_id' => $program->id,
    //         'active' => true
    //     ]);

    //     $response = $this->delete("/program/{$program->id}");

    //     $program->refresh();
    //     $subProgram->refresh();

    //     $this->assertFalse($program->active);
    //     $this->assertFalse($subProgram->active);
    // }

    /** @test */
    // public function it_deactivates_removed_sub_programs()
    // {
    //     $program = Program::create(['name' => 'Test', 'active' => true]);
    //     $sub1 = SubProgram::create(['name' => 'Sub 1', 'program_id' => $program->id, 'active' => true]);
    //     $sub2 = SubProgram::create(['name' => 'Sub 2', 'program_id' => $program->id, 'active' => true]);

    //     // Update without sub2
    //     $response = $this->put("/program/{$program->id}", [
    //         'name' => 'Test',
    //         'sub_programs' => [
    //             ['id' => $sub1->id, 'name' => 'Sub 1']
    //         ]
    //     ]);

    //     $sub2->refresh();
    //     $this->assertFalse($sub2->active);
    // }
}