<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoutTempExcel extends Model
{
    use HasFactory;
    
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
