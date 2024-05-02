<?php

namespace App\Imports;

use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\BoutKataTempExcel;


class CompKataDataImport implements ToModel, WithStartRow //ToCollection
{
    protected $competition_id;

    function __construct($competition_id) {
            $this->competition_id = $competition_id;
    }
    
    public function model(array $row)
    {
        if(isset($row[0])) {
            return new BoutKataTempExcel([
                'unique_id' => (int) $row[0],
                'bout_number' => $row[2],
                'category' => $row[3],
                'tatami' => $row[4],
                'session' => $row[5],
                'competition_id' => (int) $this->competition_id,
                'gender' => $row[1],
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