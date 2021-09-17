<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PushNotificationLog extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','company_id','no_push_notification_sent','target','status'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
