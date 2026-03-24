<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlainBillItem extends Model
{
    use HasFactory;
    
    protected $casts = [
        'weight' => 'decimal:2',
        'rate' => 'decimal:2',
    ];
    
    protected $guarded = [];

    public function bill()
    {
        return $this->belongsTo(PlainBill::class, 'invoice_id');
    }
}
