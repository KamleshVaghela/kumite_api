<?php

namespace App\Imports;

use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\BoutTempExcel;


class CompDataImport implements ToModel, WithStartRow //ToCollection
{
    protected $competition_id;

    function __construct($competition_id) {
            $this->competition_id = $competition_id;
    }
    
    public function model(array $row)
    {
        if(isset($row[0])) {
            return new BoutTempExcel([
                'unique_id' => (int) $row[0],
                'bout_number' => $row[2],
                'category' => $row[3],
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
