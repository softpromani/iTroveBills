<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
public static function  moduleStatusId($module,$status){
    return Status::where('for_module',$module)->where('status',$status)->first()->id;
}
    protected $guarded = [];
}
