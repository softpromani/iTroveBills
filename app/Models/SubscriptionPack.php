<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPack extends Model
{

    use HasFactory,SoftDeletes;
    protected $guarded = [];
    protected $appends=['features'];

    public function getFeaturesAttribute()
    {
        $month = $this->month ?? 'Unlimited Month';
        $bills = $this->no_of_bills_inmonth ?? 'Unlimited Bills';
        $email = $this->no_of_email_inmonth ?? 'Unlimited Emails';
        $customers = $this->no_of_customer ?? 'Unlimited Customers';
        $company = $this->no_of_company ?? 'Unlimited Company';

        return [
            'month' => $month,
            'bills' => $bills,
            'email' => $email,
            'customers' => $customers,
            'company' => $company,
        ];
    }
}
