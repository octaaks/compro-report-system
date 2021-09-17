<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','company_id','title','content','url','type','created_at'];
    protected $hidden = ['updated_at','deleted_at'];
}
