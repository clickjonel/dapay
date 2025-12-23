<?php

namespace Database\Seeders;

use App\Models\Disaggregation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisaggregationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disaggregations = [
            [
                'name' => 'Male',
                'active' => true,
                'type' => 'Sex'
            ],
            [
                'name' => 'Female',
                'active' => true,
                'type' => 'Sex'
            ],
            [
                'name' => '4Ps',
                'active' => true,
                'type' => 'Social Welfare'
            ],
            [
                'name' => 'Not Identified',
                'active' => true,
                'type' => 'Sex'
            ],
            [
                'name' => '0-11 months old',
                'active' => true,
                'type' => 'Age'
            ],
            [
                'name' => '12-23 months old',
                'active' => true,
                'type' => 'Age'
            ],
            [
                'name' => '6-11 months old',
                'active' => true,
                'type' => 'Age'
            ],
            [
                'name' => '12-59 months old',
                'active' => true,
                'type' => 'Age'
            ],
            [
                'name' => '6-59 months old',
                'active' => true,
                'type' => 'Age'
            ],
            [
                'name' => 'IFA',
                'active' => true,
                'type' => 'Miconutrient Supplement'
            ],
            [
                'name' => 'MMS',
                'active' => true,
                'type' => 'Miconutrient Supplement'
            ],
            [
                'name' => '10-14 years old',
                'active' => true,
                'type' => 'Age'
            ],
            [
                'name' => '15-19 years old',
                'active' => true,
                'type' => 'Age'
            ],
            [
                'name' => '20-49 years old',
                'active' => true,
                'type' => 'Age'
            ],
            [
                'name' => '15 years old and Above',
                'active' => true,
                'type' => 'Age'
            ],
            [
                'name' => 'Level I (Point Source)',
                'active' => true,
                'type' => 'Water Source'
            ],
            [
                'name' => 'Level II (Communal Faucet System or Stand Post)',
                'active' => true,
                'type' => 'Water Source'
            ],
            [
                'name' => 'Level III (Waterworks System or Individual House Connection)',
                'active' => true,
                'type' => 'Water Source'
            ],
            [
                'name' => '20-59 years old',
                'active' => true,
                'type' => 'Age'
            ],
            [
                'name' => '60 years old above',
                'active' => true,
                'type' => 'Age'
            ],
            [
                'name' => 'Visual Inspection with Acetic Acid(VIA)',
                'active' => true,
                'type' => 'Cervical'
            ],
            [
                'name' => 'Pap Smear',
                'active' => true,
                'type' => 'Cervical'
            ],
            [
                'name' => 'Human Papillomavirus Deoxyribonucleic Acid(HPV-DNA)',
                'active' => true,
                'type' => 'Cervical'
            ],
            [
                'name' => 'Assesed Only',
                'active' => true,
                'type' => 'Cervical'
            ],
            [
                'name' => 'Treated',
                'active' => true,
                'type' => 'Cancer Status'
            ],
            [
                'name' => 'Reffered',
                'active' => true,
                'type' => 'Cancer Status'
            ],
        ];

        foreach($disaggregations as $disaggregation){
            Disaggregation::create($disaggregation);
        }
    }
}
