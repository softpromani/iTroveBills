<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SellerCustomers;
use App\Models\Status;
use DateTime;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;

class CustomerBillController extends Controller
{
    public function index()
    {
        $customers = SellerCustomers::where('seller_id', Auth::id())->get();
        $company = Company::where('seller_id', Auth::id())->get();
        $company_list = $company->map(function ($company) {
            return [
                'id' => $company->id,
                'name' => $company->company_name,
            ];
        });
        $customer_list = $customers->map(function ($customer) {
            return [
                'id' => $customer->customer_company_id,
                'name' => $customer->customer_detail['name'] . '/' . $customer->customer_detail['mobile'],
            ];
        });
        return Inertia::render('Bills/index', compact('customer_list', 'company_list'));
    }

    public function index_details(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'company_id' => 'required',
        ]);
        $company_id = $request->company_id['id'];
        $customer_id = $request->customer_id['id'];
        $customers = SellerCustomers::where('seller_id', Auth::id())->get();
        $companies = Company::where('seller_id', Auth::id())->get();
        $company_list = $companies->map(function ($company) {
            return [
                'id' => $company->id,
                'name' => $company->company_name,
            ];
        });
        $customer_list = $customers->map(function ($customer) {
            return [
                'id' => $customer->customer_company_id,
                'name' => $customer->customer_detail['name'] . '/' . $customer->customer_detail['mobile'],
            ];
        });
// financial year date
// Get today's date
$today = new DateTime();
    
// Get the current year
$currentYear = (int)$today->format('Y');

// Get the current month
$currentMonth = (int)$today->format('m');

// Determine the financial year based on the current date
if ($currentMonth < 4) {
    // If the month is before April, the financial year started last calendar year
    $financialYearStart = $currentYear - 1;
    $financialYearEnd = $currentYear;
} else {
    // If the month is April or later, the financial year starts this calendar year
    $financialYearStart = $currentYear;
    $financialYearEnd = $currentYear + 1;
}

// Format the financial year in the 'YYYY-YY' format
$financialYear = sprintf("%d-%d", $financialYearStart, substr($financialYearEnd, -2));

// end
        $company = Company::where('seller_id', Auth::id())->where('id', $company_id)->first();
        $inv_count = $company->ThisYearInvoice()->count() ?? 0;
        $inv_no = $company->invoice_series . '-' . $financialYear . '-' . str_pad($inv_count + 1, 5, '0', STR_PAD_LEFT);
        $customer_data = SellerCustomers::where('seller_id', Auth::id())->where('customer_company_id', $customer_id)->first();
        return Inertia::render('Bills/index', compact('customer_list', 'company_list', 'customer_data', 'inv_no', 'company_id', 'customer_id', 'company'));
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'invoicedata.*.*' => 'required',
            'invoicedetails.*.invoice' => 'required|string',
            'invoicedetails.*.vehical_no' => 'required|string',
            'invoicedetails.*.totalWeight' => 'required|numeric',
            'invoicedetails.*.totalTaxableValue' => 'required|numeric',
        ]);
        if ($valid) {
            $invoice_data = Invoice::create([
                'invoice_number' => $request->invoicedetails[0]['invoice'],
                'invoice_date' => now(),
                'payment_status' => Status::moduleStatusId('invoice_payment', 'unpaid'),
                'total_ammount' => $request->invoicedetails[0]['totalTaxableValue'],
                'total_weight' => $request->invoicedetails[0]['totalWeight'],
                'vehicle_no' => $request->invoicedetails[0]['vehical_no'],
                'customer_company_id' => $request->invoicedetails[0]['customer'],
                'company_id' => $request->invoicedetails[0]['company'],
            ]);

            foreach ($request->invoicedata as $key => $data) {
                InvoiceItem::create([
                    'invoice_id' => $invoice_data->id,
                    'desc_product' => $data[0],
                    'hsn_code' => $data[1],
                    'quantity' => $data[2],
                    'unit' => $data[3],
                    'weight' => $data[4],
                    'rate' => $data[5],
                ]);
            }
            return redirect()->route('company.customer.view')->with('success', 'Invoice Created Successfully');
        } else {
            return back();
        }
    }

    public function invoice_list()
    {
        $invoices = auth()->user()->invoices;
        $invoices->load('paymentStatus');
        $invoices->load('Customer');
        $invoices->load('Company');
        return inertia('invoices/invoice_list', compact('invoices'));
    }

    public function template(Request $request)
    {
        try {
            $inv_id = Crypt::decrypt($request->invoice_id);
            $invoice = Invoice::find($inv_id);
            $invoice->load('invoiceitems');
            $invoice->load('Customer');
            $invoice->load(['Company' => function ($query) {
                // Load the 'CompanyLut' relationship and order it by the 'created_at' timestamp
                $query->with(['CompanyLut' => function ($subquery) {
                    $subquery->latest()->first(); // Assuming there's a timestamp column like 'created_at' or 'updated_at'
                }]);
            }]);
        } catch (DecryptException $e) {
            $inv_id = $request->invoice_id;
            $invoice = Invoice::find($inv_id);
            $invoice->load('invoiceitems');
            $invoice->load('Customer');
            $invoice->load(['Company' => function ($query) {
                // Load the 'CompanyLut' relationship and order it by the 'created_at' timestamp
                $query->with(['CompanyLut' => function ($subquery) {
                    $subquery->latest()->first(); // Assuming there's a timestamp column like 'created_at' or 'updated_at'
                }]);
            }]);
        }
        return inertia('invoices/InvoiceTemplate', compact('invoice'));
    }

    public function edit(Request $request)
    {
        $edit_data = Invoice::find($request->invoice_id);
        $edit_data->load('invoiceitems');
        $edit_data->load('Customer');
        return Inertia::render('Bills/editbill', compact('edit_data'));
    }

    public function update(Request $request)
    {

        $valid = $request->validate([
            'invoicedata.*.*' => 'required',
            'invoicedetails.*.invoice' => 'required|string',
            'invoicedetails.*.vehical_no' => 'required|string',
            'invoicedetails.*.totalWeight' => 'required|numeric',
            'invoicedetails.*.totalTaxableValue' => 'required|numeric',
            'invoice_id' => 'required',
        ]);
        if ($valid) {
            $invoice_data = Invoice::find($request->invoice_id)->update([
                'invoice_number' => $request->invoicedetails[0]['invoice'],
                'total_ammount' => $request->invoicedetails[0]['totalTaxableValue'],
                'total_weight' => $request->invoicedetails[0]['totalWeight'],
                'vehicle_no' => $request->invoicedetails[0]['vehical_no'],
            ]);
            foreach ($request->invoicedata as $key => $data) {
                InvoiceItem::create([
                    'invoice_id' => $request->invoice_id,
                    'desc_product' => $data[0],
                    'hsn_code' => $data[1],
                    'quantity' => $data[2],
                    'unit' => $data[3],
                    'weight' => $data[4],
                    'rate' => $data[5],
                ]);
            }
            return redirect()->route('company.customer.view')->with('success', 'Invoice Updated Successfully');
        } else {
            return back();
        }
    }
}
