<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GSTInvoiceItem extends Model
{
    use HasFactory;
    protected $casts = [
        'weight'=>'decimal:2',
        'rate'=>'decimal:2',
        'gst_percentage'=>'decimal:2',
        'gst_amount'=>'decimal:2',
        'subtotal_amount'=>'decimal:2',
    ];
    protected $guarded = [];
}
