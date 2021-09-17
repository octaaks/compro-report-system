<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionsLog extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','company_id','transaction_id','order_id','status'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
