<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionPartModel extends Model
{
    use HasFactory;
    protected $connection = 'rksys_app';
    protected $table = 'PART_COMPETITION';
    protected $primaryKey = 'PART_COMP_ID';
    protected $fillable = [
      'PART_COMP_ID',
      'COMP_ID',
      'KATA',
      'KUMITE',
      'TEAM_KATA',
      'TEAM_KUMITE',
      'KATA_RES',
      'KUMITE_RES',
      'TEAM_KATA_RES',
      'TEAM_KUMITE_RES',
      'KARATE_KA_ID',
      'AGE',
      'WEIGHT',
      'MEMBER_CODE',
      'ENTDATE'      
    ];
    public $timestamps = false;
}
