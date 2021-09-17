<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SendEmailLog extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','company_id','no_email_sent','email_used_for'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
