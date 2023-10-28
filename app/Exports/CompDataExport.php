<?php

namespace App\Exports;

use App\Models\CompData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;


class CompDataExport implements FromCollection, WithHeadings
{
    protected $competition_id;

    function __construct($competition_id) {
            $this->competition_id = $competition_id;
    }

    public function collection()
    {
        $participants = DB::table('participants')
        ->where('participants.competition_id', $this->competition_id)
        ->select('id', 'gender', DB::raw(' "0" as BN'),
            DB::raw(' "TBD" as CT'), 'full_name',
            'no_of_part',
            'no_of_year', 'team', 
            'age', 'weight', 'rank',
            DB::raw(' CONCAT(external_coach_name, "( ", external_coach_code, " )") as coach'), 
            )
        ->orderBy('gender')
        ->orderBy('external_coach_name')
        ->orderBy('full_name')
        ->orderBy('age')
        ->get();
        return $participants;
    }
     public function headings(): array
    {
        return ['Unique Id', 'Gender', 'Bout Number', 'Category', 'Name', 'No of Participation', 'Membership Years', 'Team', 'Age', 'Weight', 'Rank', 'Coach'];
    }

}
