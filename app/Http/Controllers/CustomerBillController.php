<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SellerCustomers;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'id' => $customer->customer_id,
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
                'id' => $customer->customer_id,
                'name' => $customer->customer_detail['name'] . '/' . $customer->customer_detail['mobile'],
            ];
        });

        $company = Company::where('seller_id', Auth::id())->where('id', $company_id)->first();
        $inv_count = $company->ThisYearInvoice()->count() ?? 0;
        $inv_no = $company->invoice_series . '-' . date('Y') . '-' . str_pad($inv_count + 1, 5, '0', STR_PAD_LEFT);
        $customer_data = SellerCustomers::where('seller_id', Auth::id())->where('customer_id', $customer_id)->first();
        return Inertia::render('Bills/index', compact('customer_list', 'company_list', 'customer_data', 'inv_no', 'company_id', 'customer_id', 'company'));
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'invoicedata' => 'required',
            'invoicedetails' => 'required',
        ]);
        // dd($request->invoicedata);
        if ($valid) {
            $invoice_data = Invoice::create([
                'invoice_number' => $request->invoicedetails[0]['invoice'],
                'invoice_date' => now(),
                'payment_status' => Status::moduleStatusId('invoice_payment', 'unpaid'),
                'total_ammount' => $request->invoicedetails[0]['totalTaxableValue'],
                'total_weight' => $request->invoicedetails[0]['totalWeight'],
                'vehicle_no' => $request->invoicedetails[0]['vehical_no'],
                'customer_id' => $request->invoicedetails[0]['customer'],
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
}
