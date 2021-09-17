<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRegisterLog extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','company_id','company_user_id'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
