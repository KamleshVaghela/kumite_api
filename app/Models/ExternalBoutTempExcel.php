<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalBoutTempExcel extends Model
{
    use HasFactory;

    protected $table = 'external_bout_temp_excels';

    protected $fillable = [
        'external_competition_id', 
        'full_name',  'team', 'coach_name', 'rank', 'age', 'weight',
        'gender', 'category', 'age_category', 'weight_category', 'rank_category',
        'tatami', 'session', 'bout_number', 'user_id'
    ];

}