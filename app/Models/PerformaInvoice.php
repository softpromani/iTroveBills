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

        static::created(function($invoice) {
            $invoice->payment()->create(['total_amount' => $invoice->total_ammount]);
        });

        static::updated(function($invoice) {
            $attributes = ['paymentable_id' => $invoice->id, 'paymentable_type' => get_class($invoice)];
            $values = ['total_amount' => $invoice->total_ammount];
            $invoice->payment()->updateOrCreate($attributes, $values);
        });
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'paymentable');
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
    public function lut()
    {
        return $this->belongsTo(CompanyLUT::class, 'lut_id');
    }
}
