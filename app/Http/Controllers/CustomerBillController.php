<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PerformaInvoice;
use App\Models\PerformaInvoiceItem;
use App\Models\SellerCustomers;
use App\Models\Status;
use App\Models\CompanyLUT;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CustomerBillController extends Controller
{
    public function index(Request $request)
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

        if(isset($request)){
            $selectedCustId = $request->customer_id;
            return Inertia::render('Bills/index', compact('customer_list', 'company_list', 'selectedCustId'));
        }
        else{
            return Inertia::render('Bills/index', compact('customer_list', 'company_list'));
        }
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

        // Financial year calculation
        $today = new DateTime();
        $currentYear = (int)$today->format('Y');
        $currentMonth = (int)$today->format('m');

        if ($currentMonth < 4) {
            $financialYearStart = $currentYear - 1;
            $financialYearEnd = $currentYear;
        } else {
            $financialYearStart = $currentYear;
            $financialYearEnd = $currentYear + 1;
        }

        $financialYear = sprintf("%02d-%02d", substr($financialYearStart, -2), substr($financialYearEnd, -2));

        $company = Company::where('seller_id', Auth::id())->where('id', $company_id)->first();

        // Check latest non-expired LUT
        $latestLut = CompanyLut::where('company_id', $company_id)
            ->where('expiry_date', '>=', $today->format('Y-m-d'))
            ->where('status', 3) // Active LUT
            ->latest()
            ->first();

        // Determine starting invoice number
        $startingNumber = 0;
        if ($latestLut && !empty($latestLut->starting_bill_count)) {
            $startingNumber = (int)$latestLut->starting_bill_count;
        }

        // Get existing invoice numbers for this company & financial year
        $existingInvoices = $company->ThisYearInvoice()->pluck('invoice_number')->toArray();

        // Extract numeric part from invoice numbers
        $existingNumbers = [];
        foreach ($existingInvoices as $inv) {
            $parts = explode('/', $inv); // Format: SERIES-YY/000XX
            if (count($parts) === 2) {
                $num = (int)$parts[1];
                $existingNumbers[] = $num;
            }
        }

        // Determine next available number
        if (!empty($existingNumbers)) {
            $maxExisting = max($existingNumbers);
            $billCount = max($startingNumber, $maxExisting + 1);
        } else {
            $billCount = $startingNumber > 0 ? $startingNumber : ($company->ThisYearInvoice()->count() ?? 0) + 1;
        }

        $inv_no = $company->invoice_series . '-' . $financialYear . '/' . str_pad($billCount, 5, '0', STR_PAD_LEFT);

        $customer_data = SellerCustomers::where('seller_id', Auth::id())
            ->where('customer_company_id', $customer_id)
            ->first();

        return Inertia::render('Bills/index', compact(
            'customer_list',
            'company_list',
            'customer_data',
            'inv_no',
            'company_id',
            'customer_id',
            'company'
        ));
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
            $lut = CompanyLUT::where('company_id', $request->invoicedetails[0]['company'])->latest()->first();
            $invoice_data = Invoice::create([
                'invoice_number' => $request->invoicedetails[0]['invoice'],
                'invoice_date' => now(),
                'payment_status' => Status::moduleStatusId('invoice_payment', 'unpaid'),
                'total_ammount' => $request->invoicedetails[0]['totalTaxableValue'],
                'total_weight' => $request->invoicedetails[0]['totalWeight'],
                'vehicle_no' => $request->invoicedetails[0]['vehical_no'],
                'customer_company_id' => $request->invoicedetails[0]['customer'],
                'company_id' => $request->invoicedetails[0]['company'],
                'lut_id' => isset($lut) ? $lut->id : Null,
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
            return redirect()->route('invoice.list')->with('success', 'Invoice Created Successfully');
        } else {
            return back();
        }
    }

   public function invoice_list(Request $request)
    {
        $invoicesQuery = auth()->user()->invoices();

        // Apply filters
        if ($request->filled('customer_id')) {
            // Get company_id from seller_customers table
            $companyId = SellerCustomers::where('id', $request->customer_id)
                ->value('customer_company_id');

            if ($companyId) {
                $invoicesQuery->where('customer_company_id', $companyId);
            }
        }

        if ($request->filled('customer_name')) {
            // Option 1: Search in companies table
            $customerIds = Company::where('company_name', 'like', '%' . $request->customer_name . '%')
                ->pluck('id');

            $invoicesQuery->whereIn('customer_company_id', $customerIds);

            // Option 2: If you want to search via seller_customers JSON
            /*
            $customerIds = SellerCustomers::where(
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(customer_company_data, '$.name'))"),
                'like',
                '%' . $request->customer_name . '%'
            )->pluck('customer_company_id');

            $invoicesQuery->whereIn('customer_company_id', $customerIds);
            */
        }
        if ($request->filled('financial_year')) {
            $financialYear = $request->financial_year;
            $startDate = Carbon::createFromDate($financialYear, 4, 1)->startOfDay();
            $endDate = Carbon::createFromDate($financialYear + 1, 3, 31)->endOfDay();

            $invoicesQuery->whereBetween('invoice_date', [$startDate, $endDate]);
        }

        if ($request->filled('payment_status')) {
            $invoicesQuery->whereHas('payment', function($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        // Get the filtered invoices
        $invoices = $invoicesQuery->get();
        $invoices->load('paymentStatus', 'Customer', 'Company', 'payment');

        // Get filter data
        $customers = SellerCustomers::select('id', 'customer_company_data')
            ->get()
            ->map(function($customer) {
                $detail = json_decode($customer->customer_company_data, true);
                return [
                    'id' => $customer->id,
                    'name' => $detail['name'] ?? 'Unknown',
                    'email' => $detail['email'] ?? '',
                    'mobile' => $detail['mobile'] ?? ''
                ];
            });

        $paymentStatuses = ['due', 'partial-paid', 'paid'];

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $financialYears = [];

        for ($i = 0; $i < 5; $i++) {
            $year = $currentMonth >= 4 ? $currentYear - $i : $currentYear - $i - 1;
            $financialYears[] = [
                'value' => $year,
                'label' => $year . '-' . ($year + 1)
            ];
        }

        return inertia('invoices/invoice_list', compact(
            'invoices',
            'customers',
            'paymentStatuses',
            'financialYears'
        ));
    }

    public function template(Request $request)
    {
        try {
            $inv_id = Crypt::decrypt($request->invoice_id);
            $invoice = Invoice::find($inv_id);
            $invoice->load('invoiceitems');
            $invoice->load('Customer');
            $invoice->load('lut');
            $invoice->load('Company');

        } catch (DecryptException $e) {
            $inv_id = $request->invoice_id;
            $invoice = Invoice::find($inv_id);
            $invoice->load('invoiceitems');
            $invoice->load('Customer');
            $invoice->load('lut');
            $invoice->load('Company');
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
            $invoice_data = Invoice::findOrFail($request->invoice_id);
            $invoice_data->touch();
            $invoice_data->update([
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
            return redirect()->route('invoice.list')->with('success', 'Invoice Updated Successfully');
        } else {
            return back();
        }
    }

    public function payBill(Request $request)
    {
        $invoice = Invoice::find($request->invoice_id);
        if($invoice)
        {
            $invoice->payment->payment_history()->create([
                'payment_type_id' => $request->payment_method_id,
                'amount' => $request->amount,
                'reference_no' => $request->reference,
                'remark' => $request->remark,
                'status' => 'paid'
            ]);

            return response()->json([ 'status'=> 1, 'message' => 'Bill paid successfully']);
        }
        else{
            return response()->json([ 'status' => 0, 'message' => 'Something went wrong!']);
        }

    }

    // performa
    public function performa_index(Request $request)
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

        if(isset($request)){
            $selectedCustId = $request->customer_id;
            return Inertia::render('PerformaBills/index', compact('customer_list', 'company_list', 'selectedCustId'));
        }
        else{
            return Inertia::render('PerformaBills/index', compact('customer_list', 'company_list'));
        }
    }
    public function performa_index_details(Request $request)
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

        // Financial year calculation
        $today = new DateTime();
        $currentYear = (int)$today->format('Y');
        $currentMonth = (int)$today->format('m');

        if ($currentMonth < 4) {
            $financialYearStart = $currentYear - 1;
            $financialYearEnd = $currentYear;
        } else {
            $financialYearStart = $currentYear;
            $financialYearEnd = $currentYear + 1;
        }

        $financialYear = sprintf("%02d-%02d", substr($financialYearStart, -2), substr($financialYearEnd, -2));

        $company = Company::where('seller_id', Auth::id())->where('id', $company_id)->first();

        // Check latest non-expired LUT
        $latestLut = CompanyLut::where('company_id', $company_id)
            ->where('expiry_date', '>=', $today->format('Y-m-d'))
            ->where('status', 3) // Active LUT
            ->latest()
            ->first();

        $startingNumber = 0;
        if ($latestLut && !empty($latestLut->starting_bill_count)) {
            $startingNumber = (int)$latestLut->starting_bill_count;
        }

        // Get existing Performa invoice numbers for this company & financial year
        $existingInvoices = $company->ThisYearPerformaInvoice()->pluck('invoice_number')->toArray();

        // Extract numeric part from invoice numbers
        $existingNumbers = [];
        foreach ($existingInvoices as $inv) {
            $parts = explode('/', $inv); // Format: SERIES-YY/000XX
            if (count($parts) === 2) {
                $num = (int)$parts[1];
                $existingNumbers[] = $num;
            }
        }

        // Determine next available invoice number
        if (!empty($existingNumbers)) {
            $maxExisting = max($existingNumbers);
            $billCount = max($startingNumber, $maxExisting + 1);
        } else {
            $billCount = $startingNumber > 0 ? $startingNumber : ($company->ThisYearPerformaInvoice()->count() ?? 0) + 1;
        }

        $inv_no = $company->invoice_series . '-' . $financialYear . '/' . str_pad($billCount, 5, '0', STR_PAD_LEFT);

        $customer_data = SellerCustomers::where('seller_id', Auth::id())
            ->where('customer_company_id', $customer_id)
            ->first();

        return Inertia::render('PerformaBills/index', compact(
            'customer_list',
            'company_list',
            'customer_data',
            'inv_no',
            'company_id',
            'customer_id',
            'company'
        ));
    }

    public function performa_store(Request $request)
    {
        $valid = $request->validate([
            'invoicedata.*.*' => 'required',
            'invoicedetails.*.invoice' => 'required|string',
            'invoicedetails.*.vehical_no' => 'required|string',
            'invoicedetails.*.totalWeight' => 'required|numeric',
            'invoicedetails.*.totalTaxableValue' => 'required|numeric',
        ]);
        if ($valid) {
            $lut = CompanyLUT::where('company_id', $request->invoicedetails[0]['company'])->latest()->first();
            $invoice_data = PerformaInvoice::create([
                'invoice_number' => $request->invoicedetails[0]['invoice'],
                'invoice_date' => now(),
                'payment_status' => Status::moduleStatusId('invoice_payment', 'unpaid'),
                'total_ammount' => $request->invoicedetails[0]['totalTaxableValue'],
                'total_weight' => $request->invoicedetails[0]['totalWeight'],
                'vehicle_no' => $request->invoicedetails[0]['vehical_no'],
                'customer_company_id' => $request->invoicedetails[0]['customer'],
                'company_id' => $request->invoicedetails[0]['company'],
                'lut_id' => isset($lut) ? $lut->id : Null,
            ]);

            foreach ($request->invoicedata as $key => $data) {
                PerformaInvoiceItem::create([
                    'invoice_id' => $invoice_data->id,
                    'desc_product' => $data[0],
                    'hsn_code' => $data[1],
                    'quantity' => $data[2],
                    'unit' => $data[3],
                    'weight' => $data[4],
                    'rate' => $data[5],
                ]);
            }
            return redirect()->route('performa.invoice.list')->with('success', 'Performa Invoice Created Successfully');
        } else {
            return back();
        }
    }

    public function performa_edit(Request $request)
    {
        $edit_data = PerformaInvoice::find($request->invoice_id);
        $edit_data->load('invoiceitems');
        $edit_data->load('Customer');
        return Inertia::render('PerformaBills/editbill', compact('edit_data'));
    }

    public function performa_update(Request $request)
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
            $invoice_data = PerformaInvoice::findOrFail($request->invoice_id);
            $invoice_data->touch();
            $invoice_data->update([
                'invoice_number' => $request->invoicedetails[0]['invoice'],
                'total_ammount' => $request->invoicedetails[0]['totalTaxableValue'],
                'total_weight' => $request->invoicedetails[0]['totalWeight'],
                'vehicle_no' => $request->invoicedetails[0]['vehical_no'],
            ]);

            foreach ($request->invoicedata as $key => $data) {
                PerformaInvoiceItem::create([
                    'invoice_id' => $request->invoice_id,
                    'desc_product' => $data[0],
                    'hsn_code' => $data[1],
                    'quantity' => $data[2],
                    'unit' => $data[3],
                    'weight' => $data[4],
                    'rate' => $data[5],
                ]);
            }
            return redirect()->route('performa.invoice.list')->with('success', 'Proforma Invoice Updated Successfully');
        } else {
            return back();
        }
    }

    public function performa_invoice_list()
    {
        $invoices = auth()->user()->performa_invoices;
        // $invoices->load('paymentStatus');
        $invoices->load('Customer');
        $invoices->load('Company');
        // $invoices->load('payment');
        return inertia('Performa-invoices/invoice_list', compact('invoices'));
    }
    public function performa_template(Request $request)
    {
        try {
            $inv_id = Crypt::decrypt($request->invoice_id);
            $invoice = PerformaInvoice::find($inv_id);
            $invoice->load('invoiceitems');
            $invoice->load('Customer');
            $invoice->load('lut');
            $invoice->load('Company');
        } catch (DecryptException $e) {
            $inv_id = $request->invoice_id;
            $invoice = PerformaInvoice::find($inv_id);
            $invoice->load('invoiceitems');
            $invoice->load('Customer');
            $invoice->load('Company');
        }
        return inertia('Performa-invoices/InvoiceTemplate', compact('invoice'));
    }
    public function myBill()
    {
        $invoices = auth()->user()->myinvoices;
        $invoices->load('paymentStatus');
        $invoices->load('Customer');
        $invoices->load('Company');
        $invoices->load('payment');
        return inertia('my-invoices/invoice_list', compact('invoices'));
    }

    public function Fetch_bill_create_payment_for_old_data(){
        $invoices=Invoice::get();
        foreach($invoices as $inv){
           $res= $inv->payment()->firstOrCreate([
                'paid_amount'=>0.00,
                'total_amount'=>$inv->total_ammount
            ]);
          echo json_encode($res);
        }
    }
}
