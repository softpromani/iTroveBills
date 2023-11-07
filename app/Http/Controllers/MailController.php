<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BillMail;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class MailController extends Controller
{
    public function billmail(Request $request)
    {
        $invoice_data = Invoice::with('Customer')->find($request->invoice_id);
        $invoice_company_data = Invoice::with('Company')->find($request->invoice_id);


        $data["email"] = $invoice_company_data->Company->email;
        $data["invoice_id"] = $invoice_data->id;
        $data["name"] = $invoice_data->Customer->company_name;
        $data["billdate"] = $invoice_data->invoice_date;
        $data["Seller_Company"] = $invoice_company_data->Company->company_name;
        $data["invoice_number"] = $invoice_data->invoice_number;

        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new BillMail($data));
        return redirect()->back()->with('success','Mail Send Successfully');
    }
}

