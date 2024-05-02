<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoutKataTempExcel extends Model
{
    use HasFactory;
    
    protected $table = 'bout_kata_temp_excels';
    
    protected $fillable = [
        'unique_id', 
        'bout_number', 
        'category', 
        'tatami',
        'session',
        'competition_id',
        'gender',
    ];

}