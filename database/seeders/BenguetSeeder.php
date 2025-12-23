<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Municipality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BenguetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('app/Benguet_Seeder.csv');
        $file = fopen($path, 'r');
        
        $headers = fgetcsv($file); // Get headers
        $rows = [];
        
        while (($row = fgetcsv($file)) !== false) {
            $rows[] = array_combine($headers, $row);
        }
        
        fclose($file);

        // dd($rows[0]);
        
        //create municipalities
        foreach($rows as $row){
            if(is_null(Municipality::where('name',$row['municipality'])->first())){
                Municipality::create([
                    'name' => $row['municipality'],
                    'province_id' => 2
                ]); 
            }
        }

        //create barangays
        foreach($rows as $row){
            Barangay::create([
                'province_id' => 2,
                'municipality_id' => Municipality::where('province_id',2)->where('name',$row['municipality'])->first()->id,
                'name' => $row['barangay'],
                'pk_status' => $row['pk_status'],
                'is_gida' => null,
                'is_pk_site' => $row['pk_status'] === 'Monitoring PK Implementation' ? true : false,
                'deployed_hrh' => null,
                'latitude' => null,
                'longitude' => null,
                'total_purok' => $row['total_puroks'] ?: null,
                'target_purok' => $row['target_purok'] ?: null,
                'target_households' => $row['target_household'] ?: null,
                'target_individuals' => $row['target_individuals'] ?: null,
                // 'total_lsa_conducted' => $row['total_lsa'] ?: null,
                // 'total_ssa_conducted' => null,
            ]);
        }

        //create reports and value
        
    }
}
