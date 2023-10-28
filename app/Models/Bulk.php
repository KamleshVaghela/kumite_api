<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Bulk extends Model
{
    protected $table = 'bulk';
    protected $fillable = [
        'name', 'email',
    ];
}