<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    protected $table = 'cities';
    use SoftDeletes;
    protected $fillable = ['id','name','transaction','total','percentage'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}