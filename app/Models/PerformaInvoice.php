<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformaInvoice extends Model
{
    use HasFactory;
    protected $guarded = [];
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
        return $this->hasMany(PerformaInvoiceItem::class,'invoice_id');
    }
}
