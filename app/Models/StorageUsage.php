<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class StorageUsage extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','company_id','used','available'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
