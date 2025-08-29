<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BillMail;
use App\Models\GSTInvoice;
use App\Models\Invoice;
use App\Models\PerformaInvoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $data["company_id"] = $invoice_data->Customer->id;
        $data["invoice_number"] = $invoice_data->invoice_number;

        Mail::to($invoice_data->Customer->email)->send(new BillMail($data));
        return redirect()->back()->with('success','Mail Sent Successfully');
    }

    public function performa_billmail(Request $request)
    {
        $invoice_data = PerformaInvoice::with('Customer')->find($request->invoice_id);
        $invoice_company_data = PerformaInvoice::with('Company')->find($request->invoice_id);


        $data["email"] = $invoice_company_data->Company->email;
        $data["invoice_id"] = $invoice_data->id;
        $data["name"] = $invoice_data->Customer->company_name;
        $data["billdate"] = $invoice_data->invoice_date;
        $data["Seller_Company"] = $invoice_company_data->Company->company_name;
        $data["company_id"] = $invoice_data->Customer->id;
        $data["invoice_number"] = $invoice_data->invoice_number;

        Mail::to($invoice_data->Customer->email)->send(new BillMail($data));
        return redirect()->back()->with('success','Mail Sent Successfully');
    }

    public function gst_billmail(Request $request)
    {
        $invoice_data = GSTInvoice::with('Customer')->find($request->invoice_id);
        $invoice_company_data = GSTInvoice::with('Company')->find($request->invoice_id);


        $data["email"] = $invoice_company_data->Company->email;
        $data["invoice_id"] = $invoice_data->id;
        $data["name"] = $invoice_data->Customer->company_name;
        $data["billdate"] = $invoice_data->invoice_date;
        $data["Seller_Company"] = $invoice_company_data->Company->company_name;
        $data["company_id"] = $invoice_data->Customer->id;
        $data["invoice_number"] = $invoice_data->invoice_number;

        Mail::to($invoice_data->Customer->email)->send(new BillMail($data));
        return redirect()->back()->with('success','Mail Sent Successfully');
    }
}

