<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
class CompanyLUT extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    protected $appends = ['formatted_expiry_date'];
    public function getFormattedExpiryDateAttribute()
{
    return $this->expiry_date ? Carbon::parse($this->expiry_date)->format('d-M-Y') : null;
}
}
