<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\SubProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'Nutrition Program',
                'active' => true,
                'sub_programs' => [
                    [
                        'name' => 'Nutrition Program',
                        'active' => true,  
                    ]
                ]
            ],
            [
                'name' => 'Immunization Program',
                'active' => true,
                'sub_programs' => [
                    [
                        'name' => 'Immunization Program',
                        'active' => true,  
                    ]
                ]
            ],
            [
                'name' => 'Water, Sanitation and Hygiene(WASH) Program',
                'active' => true,
                'sub_programs' => [
                    [
                        'name' => 'Water, Sanitation and Hygiene(WASH) Program',
                        'active' => true,  
                    ]
                ]
            ],
            [
                'name' => 'Tuberculosis Program',
                'active' => true,
                'sub_programs' => [
                    [
                        'name' => 'Tuberculosis Program',
                        'active' => true,  
                    ]
                ]
            ],
            [
                'name' => 'HIV and AIDS Program',
                'active' => true,
                'sub_programs' => [
                    [
                        'name' => 'HIV and AIDS Program',
                        'active' => true,  
                    ]
                ]
            ],
            [
                'name' => 'Road Safety Program',
                'active' => true,
                'sub_programs' => [
                    [
                        'name' => 'Road Safety Program',
                        'active' => true,  
                    ]
                ]
            ],
            [
                'name' => 'Maternal Health Program',
                'active' => true,
                'sub_programs' => [
                    [
                        'name' => 'Maternal Health Program',
                        'active' => true,  
                    ]
                ]
            ],
            [
                'name' => 'Mental Health Program',
                'active' => true,
                'sub_programs' => [
                    [
                        'name' => 'Mental Health Program',
                        'active' => true,  
                    ]
                ]
            ],
            [
                'name' => 'Non-Communicable Diseases Program',
                'active' => true,
                'sub_programs' => [
                    [
                        'name' => 'Diabetes Program',
                        'active' => true,  
                    ],
                    [
                        'name' => 'Hypertension Program',
                        'active' => true,  
                    ],
                    [
                        'name' => 'Breast Cancer Program',
                        'active' => true,  
                    ],
                    [
                        'name' => 'Cervical Cancer Program',
                        'active' => true,  
                    ],
                ]
            ],
        ];

        foreach($programs as $program){
            $programCreated = Program::create([
                'name' => $program['name'],
                'active' => $program['active'],  
            ]);

            foreach($program['sub_programs'] as $sub){
                SubProgram::create([
                    'name' => $sub['name'],
                    'active' => $sub['active'],  
                    'program_id' => $programCreated->id
                ]);
            }
        }
    }
}
