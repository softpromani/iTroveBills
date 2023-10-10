<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    public function CompanyLut(){
        return $this->hasMany(CompanyLUT::class,'company_id','id');
    }

    public function Invoices(){
        return $this->hasMany(Invoice::class,'company_id','id');
    }
    public function CompanyStatus()
    {
        return $this->belongsTo(Status::class,'status');
    }

    public function ThisYearInvoice(){
        return $this->Invoices()->whereBetween('invoice_date', [Carbon::parse(Carbon::now()->year.'-04-01'),Carbon::parse(Carbon::now()->addYear()->year.'-03-31')]);
    }
}
