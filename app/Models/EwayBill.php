<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EwayBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'doc_date' => 'date',
        'trans_doc_date' => 'date',
        'ewb_created_at' => 'datetime',
        'total_value' => 'decimal:2',
        'tot_inv_value' => 'decimal:2',
        'cgst_value' => 'decimal:2',
        'sgst_value' => 'decimal:2',
        'igst_value' => 'decimal:2',
        'cess_value' => 'decimal:2',
        'cess_non_advol_value' => 'decimal:2',
        'ewb_quantity' => 'decimal:2',
    ];

    // Example relation if supervisor is a user
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // If you want to link items separately
    public function items()
    {
        return $this->hasMany(EwayBillItem::class);
    }

}
