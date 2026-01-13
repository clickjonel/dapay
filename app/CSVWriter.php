<?php

namespace App;


class CSVWriter
{

    /**
     * Create a new class instance.
     */
    public function __construct()
    {

//         use PhpOffice\PhpSpreadsheet\Reader\Csv;
// use PhpOffice\PhpSpreadsheet\Writer\Csv as CsvWriter;
        // $path = storage_path('app/dapay-ifugao.csv');

        // $reader = new Csv();
        // $spreadsheet = $reader->load($path);
        // $sheet = $spreadsheet->getActiveSheet();

        // $headerRow = $sheet->toArray()[0];

        // $barangayCol = array_search('barangay', $headerRow);
        // $barangayIdCol = array_search('barangay_id', $headerRow);

        // $rowCount = $sheet->getHighestRow();

        // for ($row = 2; $row <= $rowCount; $row++) {
        //     $provinceName = $sheet->getCell([1, $row])->getValue();
        //     $municipalityName = $sheet->getCell([2, $row])->getValue();
        //     $barangayName = $sheet->getCell([$barangayCol + 1, $row])->getValue();
            
        //     $barangays = Barangay::where('province_id',5)->where('name', $barangayName)->get();

        //     if($barangays->count() === 1){
        //         $sheet->getCell([$barangayIdCol + 1, $row])->setValue($barangays->first()->id);
        //     }
        // }

        // $writer = new Writer($spreadsheet);
        // $writer->save($path);
    }
}
