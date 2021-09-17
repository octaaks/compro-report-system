<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','company_id','transaction_id','transaction_created_at','order_id','total_price','status','name','email','phone','address'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
