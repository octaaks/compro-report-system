<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','company_id','post_id','product_name','stock'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
