<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportDailyUserRegister extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','company_id','date','total_user_register'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
