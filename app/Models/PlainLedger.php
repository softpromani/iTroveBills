<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlainLedger extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sellerCustomer()
    {
        return $this->belongsTo(SellerCustomers::class, 'seller_customer_id');
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
