<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded= [];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($payment) {
            if ($payment->paid_amount >= $payment->total_amount && $payment->total_amount > 0) {
                $payment->status = 'paid';
            } elseif ($payment->paid_amount > 0) {
                $payment->status = 'partial-paid';
            } else {
                $payment->status = 'due';
            }
        });
    }

    public function payment_history()
    {
        return $this->hasMany(PaymentHistory::class, 'payment_id', 'id');
    }

    public function paymentable()
    {
        return $this->morphTo();
    }
}
