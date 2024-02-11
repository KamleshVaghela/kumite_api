<?php

namespace App\Exports;

use App\Models\CompData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;


class ExternalCompDataExport implements FromCollection, WithHeadings
// , WithEvents 
{
    protected $external_comp_id;
    protected $data;

    function __construct($external_comp_id) {
            $this->external_comp_id = $external_comp_id;
    }

    public function collection()
    {
        $participants = DB::table('external_participants')
        ->where('external_participants.external_competition_id', $this->external_comp_id)
        ->select( 'full_name', 'gender', 'team', 'coach_name', 'rank', 'age', 'weight',
            DB::raw(' "TBD" as category'), DB::raw(' "" as age_category'), DB::raw(' "" as weight_category'),
            DB::raw(' "" as rank_category'), DB::raw(' "0" as tatami'), DB::raw(' "TBD" as session'), DB::raw(' "0" as bout_number'),
        )
        ->orderBy('gender')
        ->orderBy('age')
        ->orderBy('weight')
        ->get();
        $this->data = $participants;
        return $participants;
    }
    public function headings(): array
    {
        return ['Name', 'Gender',  'Team', 'Coach', 'Rank', 'Age', 'Weight',  'Category', 'Age Category', 'Weight Category','Rank Category', 'Tatami', 'Session', 'Bout Number', ];
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function(AfterSheet $event) {
    //             // Get the total number of rows
    //             $totalRows = $this->data->count();
    
    //             // Loop through each row and apply conditional formatting
    //             for ($row = 2; $row <= $totalRows + 1; $row++) {
    //                 $noOfYear = $event->sheet->getCell('I' . $row)->getValue();
    //                 $rank = $event->sheet->getCell('M' . $row)->getValue();
                    
    //                 $list_rank = array('White','White-1','Yellow');

    //                 if ( in_array($rank, $list_rank) && $noOfYear > 3 ) {
    //                     $event->sheet->getStyle('A' . $row.':N' . $row)->getFont()->setBold(true);
    //                     $event->sheet->getStyle('A' . $row.':N' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00FF7F');
    //                 }
    //             }
    //         },
    //     ];
    // }
}