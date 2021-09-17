<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportDailyPushNotification extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','company_id','date','total_push_notification'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
