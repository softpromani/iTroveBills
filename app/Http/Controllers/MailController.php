<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BillMail;
use App\Models\GSTInvoice;
use App\Models\Invoice;
use App\Models\PerformaInvoice;
use App\Models\PlainBill;
use Illuminate\Support\Facades\Log;
use Throwable;

class MailController extends Controller
{
    public function billmail(Request $request)
    {
        $invoice = Invoice::with(['Customer', 'Company'])->find($request->invoice_id);

        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice not found.');
        }

        $customer = $invoice->Customer;
        if (!$customer || empty($customer->email)) {
            return redirect()->back()->with('error', 'Customer email address is missing for this invoice.');
        }

        $data = [
            "email" => $invoice->Company->email ?? config('mail.from.address'),
            "invoice_id" => $invoice->id,
            "name" => $customer->company_name ?? $customer->name ?? 'Customer',
            "billdate" => $invoice->invoice_date,
            "Seller_Company" => $invoice->Company->company_name ?? 'Company',
            "company_id" => $customer->id,
            "invoice_number" => $invoice->invoice_number,
        ];

        try {
            Mail::to($customer->email)->send(new BillMail($data));
            return redirect()->back()->with('success', 'Mail Sent Successfully');
        } catch (Throwable $e) {
            Log::error('Error sending bill mail: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send mail: ' . $e->getMessage());
        }
    }

    public function performa_billmail(Request $request)
    {
        $invoice = PerformaInvoice::with(['Customer', 'Company'])->find($request->invoice_id);

        if (!$invoice) {
            return redirect()->back()->with('error', 'Proforma invoice not found.');
        }

        $customer = $invoice->Customer;
        if (!$customer || empty($customer->email)) {
            return redirect()->back()->with('error', 'Customer email address is missing for this invoice.');
        }

        $data = [
            "email" => $invoice->Company->email ?? config('mail.from.address'),
            "invoice_id" => $invoice->id,
            "name" => $customer->company_name ?? $customer->name ?? 'Customer',
            "billdate" => $invoice->invoice_date,
            "Seller_Company" => $invoice->Company->company_name ?? 'Company',
            "company_id" => $customer->id,
            "invoice_number" => $invoice->invoice_number,
        ];

        try {
            Mail::to($customer->email)->send(new BillMail($data));
            return redirect()->back()->with('success', 'Mail Sent Successfully');
        } catch (Throwable $e) {
            Log::error('Error sending performa bill mail: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send mail: ' . $e->getMessage());
        }
    }

    public function gst_billmail(Request $request)
    {
        $invoice = GSTInvoice::with(['Customer', 'Company'])->find($request->invoice_id);

        if (!$invoice) {
            return redirect()->back()->with('error', 'GST invoice not found.');
        }

        $customer = $invoice->Customer;
        if (!$customer || empty($customer->email)) {
            return redirect()->back()->with('error', 'Customer email address is missing for this invoice.');
        }

        $data = [
            "email" => $invoice->Company->email ?? config('mail.from.address'),
            "invoice_id" => $invoice->id,
            "name" => $customer->company_name ?? $customer->name ?? 'Customer',
            "billdate" => $invoice->invoice_date,
            "Seller_Company" => $invoice->Company->company_name ?? 'Company',
            "company_id" => $customer->id,
            "invoice_number" => $invoice->invoice_number,
        ];

        try {
            Mail::to($customer->email)->send(new BillMail($data));
            return redirect()->back()->with('success', 'Mail Sent Successfully');
        } catch (Throwable $e) {
            Log::error('Error sending GST bill mail: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send mail: ' . $e->getMessage());
        }
    }

    public function plain_billmail(Request $request)
    {
        $invoice = PlainBill::with(['Customer', 'Company'])->find($request->invoice_id);

        if (!$invoice) {
            return redirect()->back()->with('error', 'Plain bill not found.');
        }

        $customer = $invoice->Customer;
        if (!$customer || empty($customer->email)) {
            return redirect()->back()->with('error', 'Customer email address is missing for this invoice.');
        }

        $data = [
            "email" => $invoice->Company->email ?? config('mail.from.address'),
            "invoice_id" => $invoice->id,
            "name" => $customer->company_name ?? $customer->name ?? 'Customer',
            "billdate" => $invoice->invoice_date,
            "Seller_Company" => $invoice->Company->company_name ?? 'Company',
            "company_id" => $customer->id,
            "invoice_number" => $invoice->invoice_number,
        ];

        try {
            Mail::to($customer->email)->send(new BillMail($data));
            return redirect()->back()->with('success', 'Mail Sent Successfully');
        } catch (Throwable $e) {
            Log::error('Error sending plain bill mail: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send mail: ' . $e->getMessage());
        }
    }
}
