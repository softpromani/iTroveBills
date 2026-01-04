<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterGstConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'client_id',
        'client_secret',
        'username',
        'password',
        'gstin',
        'email',
        'gst_base_url',
        'ip_address',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
