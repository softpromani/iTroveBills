<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function fetchPaymentType()
    {
        $types = PaymentType::get();

        return $types;
    }
}
