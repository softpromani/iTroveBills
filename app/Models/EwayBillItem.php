<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EwayBillItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function ewayBill()
    {
        return $this->belongsTo(EwayBill::class);
    }
}
