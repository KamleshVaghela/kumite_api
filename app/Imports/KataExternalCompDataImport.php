<?php

namespace App\Imports;

use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\KataExternalBoutTempExcel;


class KataExternalCompDataImport implements ToModel, WithStartRow //ToCollection
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
        // 'Gender', 'Bout Number',  'Category', 'Age Category', 'Weight Category','Rank Category',
        // 'Tatami', 'Session', 'Name',  'Weight',  'Rank', 'Age', 'Coach','Team', 
        if(isset($row[0])) {
            return new KataExternalBoutTempExcel([
                'external_competition_id' => (int) $this->external_comp_id, 
                'full_name' => $row[8], 
                'gender' => $row[0], 
                'team' => $row[13], 
                'coach_name' => $row[12], 
                'rank' => $row[10], 
                'age' => $row[11], 
                'weight' => $row[9], 
                'category' => $row[2], 
                'age_category' => $row[3], 
                'weight_category' => $row[4], 
                'rank_category' => $row[5], 
                'tatami' => $row[6], 
                'session' => $row[7], 
                'bout_number' => $row[1],
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