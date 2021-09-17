<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportYearlyTransaction extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','company_id','order_sales','order_count','sales_per_order','status', 'start_date','end_date'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
