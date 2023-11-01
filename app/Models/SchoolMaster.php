<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolMaster extends Model
{
    use HasFactory;
    protected $connection = 'rksys_app';
    protected $table = 'school_masters';
    protected $primaryKey = 'id';
    protected $fillable = [
      'id',
      'geo_id',
      'name',
    ];
    public $timestamps = false;
}
