<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SellerSubscription extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function sellerSubscription(){
        return $this->belongsTo(SubscriptionPack::class,'subscription_id','id')->where('seller_id',Auth::id());
    }
}
