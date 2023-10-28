<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\JsonCast;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerCustomers extends Model
{
    use HasFactory,SoftDeletes;

    protected $appends=['customer_detail'];
    Protected $guarded = [];

    public function getCustomerDetailAttribute(){
        return json_decode($this->customer_company_data,true);
    }
}
