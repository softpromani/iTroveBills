<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function Company()
    {
        return $this->belongsTo(Company::class);
    }
    public function Customer()
    {
        return $this->belongsTo(User::class);
    }
    public function invoiceitems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function paymentStatus()
    {
        return $this->belongsTo(Status::class, 'payment_status');
    }
}