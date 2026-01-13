<?php

namespace Database\Seeders;

use App\Models\OrganizationalIndicators;
use App\Models\ProgramIndicators;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizational_indicators = [
            [
                'name' => 'Total Number of First Patient Encounters',
                'active' => true,
            ], 
            [
                'name' => 'Total number of referrals made to higher facilities',
                'active' => true,
            ],
            [
                'name' => 'Total number of Philhealth Registrations',
                'active' => true,
            ],
            [
                'name' => 'Total number of small-scale activities conducted',
                'active' => true,
            ],
            [
                'name' => 'Total number of large scale activities conducted',
                'active' => true,
            ],
        ];

       foreach($organizational_indicators as $organizational_indicator)
       {
         OrganizationalIndicators::create($organizational_indicator);
       }

       $program_indicators = [
            [
                'name' => 'Total number of Completely Immunized Children',
                'sub_program_id' => 2,
                'active' => true,
            ],
            [
                'name' => 'Infants who completed three doses of DPT-HiB-HepB antigen',
                'sub_program_id' => 2,
                'active' => true,
            ],
            [
                'name' => 'Infants who completed three doses of Oral Polio Vaccine',
                'sub_program_id' => 2,
                'active' => true,
            ],
            [
                'name' => 'Children vaccinated with two doses of Measles Mumps Rubella vaccine',
                'sub_program_id' => 2,
                'active' => true,
            ],
            [
                'name' => 'Infant/children who completed Vitamin A supplementation',
                'sub_program_id' => 1,
                'active' => true,
            ],
            [
                'name' => 'Severe Acute Malnutrition children without complication admitted to Outpatient Therapeutic Care',
                'sub_program_id' => 1,
                'active' => true,
            ],
            [
                'name' => 'Pregnant women who are currently consuming the recommended micronutrient supplements',
                'sub_program_id' => 1,
                'active' => true,
            ],
            [
                'name' => 'Households with access to Basic Safe Water Supply',
                'sub_program_id' => 3,
                'active' => true,
            ],
            [
                'name' => 'Households with Basic Sanitation Facility',
                'sub_program_id' => 3,
                'active' => true,
            ],
            [
                'name' => 'Clients who are presently using any Family Planning Method',
                'sub_program_id' => 7,
                'active' => true,
            ],
            [
                'name' => 'Pregnant or postpartum women who had blood pressure measured during each of their attended 8ANC and 4PNC visits',
                'sub_program_id' => 7,
                'active' => true,
            ],
            [
                'name' => 'Pregnant of postpartum women with high blood pressure or danger signs who were reffered to a higher level facility',
                'sub_program_id' => 7,
                'active' => true,
            ],
            [
                'name' => 'Individuals provided with first aid due to road crash injuries',
                'sub_program_id' => 6,
                'active' => true,
            ],
            [
                'name' => 'Individuals identified as hypertensive and with complete antihypertensive medications',
                'sub_program_id' => 10,
                'active' => true,
            ],
            [
                'name' => 'Individuals identified with type 2 Diabetes Mellitus and with complete antidiabetic medications',
                'sub_program_id' => 9,
                'active' => true,
            ],
            [
                'name' => 'Women screened or assessed for cervical cancer',
                'sub_program_id' => 12,
                'active' => true,
            ],
            [
                'name' => 'Women found positive for precancerous lesions and linked to care',
                'sub_program_id' => 12,
                'active' => true,
            ],
            [
                'name' => 'Women found suspicious for cervical cancer and linked to care',
                'sub_program_id' => 12,
                'active' => true,
            ],
            
       ];

       foreach($program_indicators as $program_indicator)
       {
         ProgramIndicators::create($program_indicator);
       }

    }
}
