<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Invoice extends Model
{
    protected $guarded = [];
    use HasFactory;
    protected $casts = [
        'total_ammount'=>'decimal:2',
        'paid_ammount'=>'decimal:2',
        'total_weight'=>'decimal:2',
    ];
    public static function boot()
    {
        parent::boot();
        static::updating(function ($invoice) {
            $invoice->invoiceitems()->delete();
        });
    }
    public function Company()
    {
        return $this->belongsTo(Company::class);
    }
    public function Customer()
    {
        return $this->belongsTo(Company::class, 'customer_company_id');
    }
    public function invoiceitems()
    {
        return $this->hasMany(InvoiceItem::class,'invoice_id');
    }
    public function paymentStatus()
    {
        return $this->belongsTo(Status::class, 'payment_status');
    }

   
}
