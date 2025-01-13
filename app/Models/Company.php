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
         // Calculate the start and end dates for the fiscal year
        $startOfYear = Carbon::now()->month >= 4 
        ? Carbon::now()->startOfYear()->addMonths(3) // April 1 of the current year
        : Carbon::now()->subYear()->startOfYear()->addMonths(3); // April 1 of the previous year
        $endOfYear = $startOfYear->copy()->addYear()->subDay(); // March 31 of the next year

        // // Debug: Log the calculated dates
        // \Log::info('Fiscal Year Start: ' . $startOfYear);
        // \Log::info('Fiscal Year End: ' . $endOfYear);
        return $this->Invoices()->whereBetween('invoice_date', [$startOfYear, $endOfYear]);
    }
}
