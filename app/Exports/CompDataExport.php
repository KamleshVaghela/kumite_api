<?php

namespace App\Exports;

use App\Models\CompData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;


class CompDataExport implements FromCollection, WithHeadings, WithEvents 
{
    protected $competition_id;
    protected $data;

    function __construct($competition_id) {
            $this->competition_id = $competition_id;
    }

    public function collection()
    {
        $participants = DB::table('participants')
        ->where('participants.competition_id', $this->competition_id)
        ->select('id', 'gender', DB::raw(' "0" as BN'),
            DB::raw(' "TBD" as CT'), DB::raw(' "0" as tatami'), DB::raw(' "TBD" as Session'), 'full_name',
            'no_of_part',
            'no_of_year', 'team', 
            'age', 'weight', 'rank',
            DB::raw(' CONCAT(external_coach_name, "( ", external_coach_code, " )") as coach') 
        )
        ->orderBy('gender')
        ->orderBy('age')
        ->orderBy('rank_id')
        ->orderBy('weight')
        ->get();
        $this->data = $participants;
        return $participants;
    }
     public function headings(): array
    {
        return ['Unique Id', 'Gender', 'Bout Number', 'Category', 'Tatami', 'Session',  'Name', 'No of Participation', 'Membership Years', 'Team', 'Age', 'Weight', 'Rank', 'Coach'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Get the total number of rows
                $totalRows = $this->data->count();
    
                // Loop through each row and apply conditional formatting
                for ($row = 2; $row <= $totalRows + 1; $row++) {
                    $noOfYear = $event->sheet->getCell('I' . $row)->getValue();
                    $rank = $event->sheet->getCell('M' . $row)->getValue();
                    
                    $list_rank = array('White','White-1','Yellow');

                    if ( in_array($rank, $list_rank) && $noOfYear > 3 ) {
                        $event->sheet->getStyle('A' . $row.':N' . $row)->getFont()->setBold(true);
                        $event->sheet->getStyle('A' . $row.':N' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00FF7F');
                    }
                }
            },
        ];
    }
}
