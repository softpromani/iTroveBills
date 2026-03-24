<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PlainBill extends Model
{
    protected $guarded = [];
    use HasFactory;
    
    protected $casts = [
        'total_ammount' => 'decimal:2',
        'paid_ammount' => 'decimal:2',
        'total_weight' => 'decimal:2',
    ];

    public static function boot()
    {
        parent::boot();
        
        static::updating(function ($bill) {
            $bill->items()->delete();
        });

        static::created(function($bill) {
            Log::info('PlainBill created - ' . json_encode($bill));
            $bill->payment()->create(['total_amount' => $bill->total_ammount]);
        });

        static::updated(function($bill) {
            $attributes = ['paymentable_id' => $bill->id, 'paymentable_type' => get_class($bill)];
            $values = ['total_amount' => $bill->total_ammount];
            $bill->payment()->updateOrCreate($attributes, $values);
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

    public function items()
    {
        return $this->hasMany(PlainBillItem::class, 'invoice_id');
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
