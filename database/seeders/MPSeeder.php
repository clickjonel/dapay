<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Report;
use App\Models\ReportValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Csv as CsvWriter;

class MPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $path = storage_path('app/dapay-mp.csv');
        $file = fopen($path, 'r');
        
        $headers = fgetcsv($file); // Get headers
        $rows = [];
        
        while (($row = fgetcsv($file)) !== false) {
            $rows[] = array_combine($headers, $row);
        }
        
        fclose($file);
        
        foreach($rows as $index => $row){
            $barangay = Barangay::find((int)$row['barangay_id'])->update([
                'total_purok' => (int)$row['total_purok'],
                'target_purok' => (int)$row['target_purok'],
                'target_households' => (int)$row['target_households'],
                'target_individuals' => (int)$row['target_individuals'],
                'pk_status' => $row['pk_status'],
                'is_pk_site' => $row['pk_status'] === 'Monitored PK Implementation' ? 1 : 0,
            ]);

            //report
            $report = Report::create([
                'total_clients' => (int) $row['total_clients'],
                'total_returning_clients' => null,
                'submitted_by' => null,
                'date' => null,
                'barangay_id' => (int)$row['barangay_id'],
            ]);

            // // Nutrition
            ReportValue::create([
                'report_id' => $report->id,
                'sub_program_id' => 1,
                'program_indicator_id' => null,
                //'organizational_indicator_id' => null,
                //'disaggregation_id' => null,
                // 'indicator_type' => null,
                'total_value' => (int) $row['total_reached_nutrition'],
            ]);

            //Immunization
            ReportValue::create([
                'report_id' => $report->id,
                'sub_program_id' => 2,
                'program_indicator_id' => null,
                //'organizational_indicator_id' => null,
                //'disaggregation_id' => null,
                // 'indicator_type' => null,
                'total_value' => (int) $row['total_reached_immunization'],
            ]);

            //maternal
            ReportValue::create([
                'report_id' => $report->id,
                'sub_program_id' => 7,
                'program_indicator_id' => null,
                //'organizational_indicator_id' => null,
                //'disaggregation_id' => null,
                // 'indicator_type' => null,
                'total_value' => (int) $row['total_reached_maternal'],
            ]);

            //tb
            ReportValue::create([
                'report_id' => $report->id,
                'sub_program_id' => 4,
                'program_indicator_id' => null,
                //'organizational_indicator_id' => null,
                //'disaggregation_id' => null,
                // 'indicator_type' => null,
                'total_value' => (int) $row['total_reached_tb'],
            ]);


            //hiv
            ReportValue::create([
                'report_id' => $report->id,
                'sub_program_id' => 5,
                'program_indicator_id' => null,
                //'organizational_indicator_id' => null,
                //'disaggregation_id' => null,
                // 'indicator_type' => null,
                'total_value' => (int) $row['total_reached_hiv'],
            ]);

            //road safety
            ReportValue::create([
                'report_id' => $report->id,
                'sub_program_id' => 6,
                'program_indicator_id' => null,
                //'organizational_indicator_id' => null,
                //'disaggregation_id' => null,
                // 'indicator_type' => null,
                'total_value' => (int) $row['total_reached_road_safety'],
            ]);

            //diabetes
            ReportValue::create([
                'report_id' => $report->id,
                'sub_program_id' => 9,
                'program_indicator_id' => null,
                //'organizational_indicator_id' => null,
                //'disaggregation_id' => null,
                // 'indicator_type' => null,
                'total_value' => (int) $row['total_reached_diabetes'],
            ]);

            //hypertension
            ReportValue::create([
                'report_id' => $report->id,
                'sub_program_id' => 10,
                'program_indicator_id' => null,
                //'organizational_indicator_id' => null,
                //'disaggregation_id' => null,
                // 'indicator_type' => null,
                'total_value' => (int) $row['total_reached_hypertension'],
            ]);

            //breast cancer
            ReportValue::create([
                'report_id' => $report->id,
                'sub_program_id' => 11,
                'program_indicator_id' => null,
                //'organizational_indicator_id' => null,
                //'disaggregation_id' => null,
                // 'indicator_type' => null,
                'total_value' => (int) $row['total_reached_breast_cancer'],
            ]);

            //cervical cancer
            ReportValue::create([
                'report_id' => $report->id,
                'sub_program_id' => 12,
                'program_indicator_id' => null,
                //'organizational_indicator_id' => null,
                //'disaggregation_id' => null,
                // 'indicator_type' => null,
                'total_value' => (int) $row['total_reached_cervical_cancer'],
            ]);

            //health promotion
            ReportValue::create([
                'report_id' => $report->id,
                'sub_program_id' => 13,
                'program_indicator_id' => null,
                //'organizational_indicator_id' => null,
                //'disaggregation_id' => null,
                // 'indicator_type' => null,
                'total_value' => (int) $row['total_reached_health_promotion'],
            ]);



        }
    }
}
