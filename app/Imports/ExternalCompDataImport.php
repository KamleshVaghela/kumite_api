<?php

namespace App\Imports;

use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\ExternalBoutTempExcel;


class ExternalCompDataImport implements ToModel, WithStartRow //ToCollection
{
    protected $external_comp_id;

    function __construct($external_comp_id) {
            $this->external_comp_id = $external_comp_id;
    }
    
    public function model(array $row)
    {

        // ->select( 'full_name', 'gender', 'team', 'coach', 'rank', 'age', 'weight',
        //     DB::raw(' "TBD" as category'), DB::raw(' "" as age_category'), DB::raw(' "" as weight_category'),
        //     DB::raw(' "" as rank_category'), DB::raw(' "0" as tatami'), DB::raw(' "TBD" as session'), DB::raw(' "0" as bout_number'),
        // 
        if(isset($row[0])) {
            return new ExternalBoutTempExcel([
                'external_competition_id' => (int) $this->external_comp_id, 
                'full_name' => $row[0], 
                'gender' => $row[1], 
                'team' => $row[2], 
                'coach_name' => $row[3], 
                'rank' => $row[4], 
                'age' => $row[5], 
                'weight' => $row[6], 
                'category' => $row[7], 
                'age_category' => $row[8], 
                'weight_category' => $row[9], 
                'rank_category' => $row[10], 
                'tatami' => $row[11], 
                'session' => $row[12], 
                'bout_number' => $row[13],
            ]);
        } else {
            return null;
        }
        // dd($this->competition_id);
    }

    // public function headingRow(): int
    // {
    //     return 1;
    // }

    public function startRow(): int
    {
        return 2;
    }

    // public function collection(Collection $collection)
    // {
    //     //
    // }
}