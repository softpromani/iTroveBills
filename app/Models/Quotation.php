<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'customer_data' => 'array',
        'cover_letter_content' => 'array',
        'terms_and_conditions' => 'array',
        'quotation_date' => 'date',
        'total_amount' => 'double',
    ];

    public function Company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function Customer()
    {
        return $this->belongsTo(SellerCustomers::class, 'customer_company_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class, 'quotation_id', 'id')->orderBy('item_order', 'asc');
    }

    public function quotationitems()
    {
        return $this->hasMany(QuotationItem::class, 'quotation_id', 'id')->orderBy('item_order', 'asc');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
