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
            $pay->payment->increment('paid_amount',$pay->amount);
            ($pay->payment->paid_amount==$pay->payment->total_amount)?$pay->payment()->update(['status'=>'paid']):$pay->payment()->update(['status'=>'partial-paid']);
        });
    }

    public function payment(){
        return $this->belongsTo(Payment::class,'payment_id');
    }
}
