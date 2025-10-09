<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class CompKataDataExport implements FromCollection, WithHeadings, WithEvents
{
    protected $competition_id;

    protected $data;

    public function __construct($competition_id)
    {
        $this->competition_id = $competition_id;
    }

    public function collection()
    {
        $participants = DB::table('participants')
        ->where('participants.competition_id', $this->competition_id)
        ->where('participants.kata', '=', '1')
        ->select('id', 'gender', DB::raw(' "0" as BN'),
            DB::raw(' "TBD" as CT'), DB::raw(' "0" as tatami'), DB::raw(' "TBD" as Session'), 'full_name',
            'no_of_part',
            'no_of_year', 'team',
            'age', 'weight', 'rank', 'rank_kyu',
            DB::raw(' CONCAT(external_coach_name, "( ", external_coach_code, " )") as coach'),
            DB::raw(' "TBD" as age_category'),
            DB::raw(' "TBD" as weight_category'),
            DB::raw(' "TBD" as rank_category')
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
         return ['Unique Id', 'Gender', 'Bout Number', 'Category', 'Tatami', 'Session',  'Name', 'No of Participation', 'Membership Years', 'Team', 'Age', 'Weight', 'Rank', 'Rank Kyu', 'Coach', 'Age Category', 'Weight Category', 'Rank Category'];
     }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $workSheet = $event->sheet->getDelegate();
                $workSheet->insertNewRowBefore(2, 1); // Add Empty Row
                $workSheet->freezePane('A2'); // freezing here

                // Get the total number of rows
                $totalRows = $this->data->count();

                // Loop through each row and apply conditional formatting
                for ($row = 2; $row <= $totalRows + 1; $row++) {
                    $noOfYear = $event->sheet->getCell('I'.$row)->getValue();
                    $rank = $event->sheet->getCell('M'.$row)->getValue();

                    $list_rank = ['White', 'White-1', 'Yellow'];

                    if (in_array($rank, $list_rank) && $noOfYear > 3) {
                        $event->sheet->getStyle('A'.$row.':N'.$row)->getFont()->setBold(true);
                        $event->sheet->getStyle('A'.$row.':N'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00FF7F');
                    }
                }
            },
        ];
    }
}
