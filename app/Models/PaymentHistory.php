<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PaymentHistory extends Model
{
    use HasFactory;
    protected $guarded= [];

    protected static function boot()
    {
        parent::boot();

        // Listen for the created event
        static::created(function ($pay) {
            $payment = $pay->payment;
            $payment->increment('paid_amount', $pay->amount);
            $payment->refresh();
            
            $status = 'partial-paid';
            if ($payment->paid_amount >= $payment->total_amount) {
                $status = 'paid';
            } elseif ($payment->paid_amount <= 0) {
                $status = 'due';
            }
            
            $payment->update(['status' => $status]);
        });
    }

    public function payment(){
        return $this->belongsTo(Payment::class,'payment_id');
    }
}
