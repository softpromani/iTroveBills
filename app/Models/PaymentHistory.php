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

        static::saved(function ($history) {
            $history->syncPayment();
        });

        static::deleted(function ($history) {
            $history->syncPayment();
        });
    }

    public function syncPayment()
    {
        $payment = $this->payment;
        if ($payment) {
            $payment->paid_amount = $payment->payment_history()->sum('amount');
            $payment->save(); // This will also trigger status update in Payment's boot
        }
    }

    public function payment(){
        return $this->belongsTo(Payment::class,'payment_id');
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }
}
