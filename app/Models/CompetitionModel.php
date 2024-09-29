<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionModel extends Model
{
    use HasFactory;
    protected $connection = 'rksys_app';
    protected $table = 'COMPETITION';
    protected $primaryKey = 'COMP_ID';
    protected $fillable = [
        'COMP_ID',
        'FEES',
        'FEES_KATA',
        'FEES_KUMITE',
        'FEES_T_KATA',
        'FEES_T_KUMITE',
        'COMP_NAME',
        'REMARKS',
        'KARATE_KA_DISPLAY',
        'TYPE',
        'GEOID',
        'STATE',
        'COACH_ID',
        'COMP_DATE',
        'COMP_END_DATE',
        'CLOSE_DATE_C',
        'CLOSE_DATE_K',
        'DIS_ID_W',
        'DIS_ID_R',
        'MEMBER_CODE',
        'ENTDATE',
        'COACH_FEES'
    ];
    public $timestamps = false;
}