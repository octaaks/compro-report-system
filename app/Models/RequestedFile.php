<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestedFile extends Model
{
    protected $table = 'requested_files';
    use SoftDeletes;
    protected $fillable = ['id','file_name','status','created_at'];
    protected $hidden = ['updated_at','deleted_at','action','data_status','type','data_date'];
}
