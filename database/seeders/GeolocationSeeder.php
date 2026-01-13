<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeolocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = DB::connection('dohis')->table('dohis_province')->where('region_id',14)->get();
        
        $provinces = $provinces->each(function($province){
            $province->municipalities = DB::connection('dohis')->table('dohis_municipality')->where('province_id',$province->province_id)->get();
            $province->municipalities->each(function($municipality) use ($province){
                $municipality->barangays = DB::connection('dohis')->table('dohis_barangay')->where('municipality_id',$municipality->municipality_id)->get();
            });
        });

        $provinces->each(function($province){
            $provinceCreated = Province::create([
                'name' => $province->province_name
            ]);

            foreach($province->municipalities as $municipality){
                $municipalityCreated = Municipality::create([
                    'name' => $municipality->municipality_name,
                    'province_id' => $provinceCreated->id
                ]);

                foreach($municipality->barangays as $barangay){
                    Barangay::create([
                        'province_id' => $provinceCreated->id,
                        'municipality_id' => $municipalityCreated->id,
                        'name' => $barangay->barangay_name,
                        'pk_status' => 'Status Not Set',
                        'is_gida' => null,
                        'is_pk_site' => 0,
                        'deployed_hrh' => null,
                        'latitude' => null,
                        'longitude' => null,
                        'total_purok' => null,
                        'target_purok' => null,
                        'target_households' => null,
                        'target_individuals' => null,
                    ]);
                }
            }
        });
    }
}
