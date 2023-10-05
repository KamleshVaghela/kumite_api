<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KarateKaModel extends Model
{
    use HasFactory;
    protected $connection = 'rksys_app';
    protected $table = 'KARATE_KA';
    protected $primaryKey = 'KARATE_KA_ID';
    protected $fillable = [
      'KARATE_KA_ID',
      'TITLE',
      'NAME',
      'M_NAME',
      'L_NAME',
      'DOB',
      'ADDRESS1',
      'ADDRESS2',
      'ADDRESS3',
      'ADDRESS4',
      'ADDRESS5',
      'CONTACT_LL',
      'CONTACT_MO',
      'CONTACT_OFF',
      'GRNUMBER',
      'PROFILE_PHOTO',
      'EMAIL',
      'ENTDATE',
      'OCC_ID',
      'DOJO_ID',
      'SM_ID',
      'COACH_ID',
      'DIS_ID',
      'MEMBER_CODE',
      'RANK_ID',
      'IS_ACTIVE',
      'IS_FORM_SUBMIT',
      'BG',
      'F_OCC',
      'F_OCC_ADD',
      'F_EMAIL',
      'M_OCC',
      'M_OCC_ADD',
      'M_EMAIL',
      'IS_TRANSFER',
      'REG_MOBILE',
      'AVATAR',
    ];
    public $timestamps = false;
}
