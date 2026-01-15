<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\SubProgram;
use App\Models\User;
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
                'username' => 'program-nutrition',
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
                'username' => 'program-immunization',
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
                'username' => 'program-wash',
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
                'username' => 'program-tb',
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
                'username' => 'program-hiv-aids',
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
                'username' => 'program-road-safety',
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
                'username' => 'program-maternal-health',
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
                'username' => 'program-mental-health',
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
                'username' => 'program-ncd',
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
            [
                'name' => 'Health Promotion Program',
                'active' => true,
                'username' => 'program-health-promotion',
                'sub_programs' => [
                    [
                        'name' => 'Health Promotion Program',
                        'active' => true,
                    ]
                ]
            ],
        ];

        foreach($programs as $program){
            $programCreated = Program::create([
                'name' => $program['name'],
                'active' => $program['active'],  
            ]);

            User::create([
                'username' => $program['username'],
                'password' => '12345',
                'first_name' => 'Program',
                'middle_name' => ' - ',
                'last_name' => $program['name'],
                'suffix' => null,
                'prefix' => null,
                'nickname' => $program['name'],
                'account_status' => 'Active',
                'user_level' => 4,
                'pdoho_province_id' => null,
                'program_id' => $programCreated->id
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
