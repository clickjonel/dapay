<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            [
                'province_name' => 'Abra',
            ],
            [
                'province_name' => 'Benguet',
            ],
            [
                'province_name' => 'Ifugao',
            ],
            [
                'province_name' => 'Kalinga',
            ],
            [
                'province_name' => 'Mountain Province',
            ],
            [
                'province_name' => 'Apayao',
            ],
            [
                'province_name' => 'City of Baguio',
            ],
        ];

        foreach($provinces as $province){
            Province::create([
                'name' => $province['province_name']
            ]);
        }
    }
}
