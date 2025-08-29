<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class GSTInvoice extends Model
{
    protected $guarded = [];
    use HasFactory;
    protected $casts = [
        'total_ammount'=>'decimal:2',
        'paid_ammount'=>'decimal:2',
        'total_weight'=>'decimal:2',
        'gst_amount'=>'decimal:2',
        'subtotal_amount'=>'decimal:2',
    ];
    public static function boot()
    {
        parent::boot();
        static::updating(function ($invoice) {
            $invoice->invoiceitems()->delete();
        });

        static::created(function($invoice) {
            // Create a payment record associated with the invoice
            Log::info('created event -'.json_encode($invoice));
            $invoice->payment()->create(['total_amount' => $invoice->total_ammount]);
        });


        static::updated(function($invoice) {
            // Define the attributes to find the existing payment
            $attributes = ['paymentable_id' => $invoice->id, 'paymentable_type' => get_class($invoice)];

            // Define the values to update or create
            $values = ['total_amount' => $invoice->total_ammount];

            // Use updateOrCreate
            $invoice->payment()->updateOrCreate($attributes, $values);
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
        return $this->hasMany(GSTInvoiceItem::class,'invoice_id');
    }
    public function paymentStatus()
    {
        return $this->belongsTo(Status::class, 'payment_status');
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

    public function lut()
    {
        return $this->belongsTo(CompanyLUT::class, 'lut_id');
    }
}
