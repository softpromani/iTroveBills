<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PlainBill;
use App\Models\PlainBillItem;
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
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class PlainBillController extends Controller
{
    public function index(Request $request)
    {
        $needs_auth = $request->attributes->get('needs_plain_bill_auth', false);

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

        $inv_no = null;
        $company_obj = null;
        $customer_data = null;
        $company_id_val = null;
        $customer_id_val = null;

        if ($request->filled('company_id')) {
            $company_id_val = is_array($request->company_id) ? $request->company_id['id'] : $request->company_id;
            $customer_id_val = $request->filled('customer_id') ? (is_array($request->customer_id) ? $request->customer_id['id'] : $request->customer_id) : null;

            // Financial year calculation
            $today = new DateTime();
            $currentYear = (int)$today->format('Y');
            $currentMonth = (int)$today->format('m');
            $financialYearStart = $currentMonth < 4 ? $currentYear - 1 : $currentYear;
            $financialYearEnd = $financialYearStart + 1;
            $financialYear = sprintf("%02d-%02d", substr($financialYearStart, -2), substr($financialYearEnd, -2));

            $company_obj = Company::where('seller_id', Auth::id())->where('id', $company_id_val)->first();
            
            // Determine starting invoice number (Reusing lookup from regular invoices)
            $existingInvoices = PlainBill::where('company_id', $company_id_val)
                ->whereBetween('invoice_date', [Carbon::createFromDate($financialYearStart, 4, 1), Carbon::createFromDate($financialYearEnd, 3, 31)])
                ->pluck('invoice_number')->toArray();

            $existingNumbers = [];
            foreach ($existingInvoices as $inv) {
                $parts = explode('/', $inv);
                if (count($parts) === 2) {
                    $existingNumbers[] = (int)$parts[1];
                }
            }

            $billCount = !empty($existingNumbers) ? max($existingNumbers) + 1 : 1;
            $inv_no = 'INV-PB-' . $financialYear . '/' . str_pad($billCount, 5, '0', STR_PAD_LEFT);

            if ($customer_id_val) {
                $customer_data = SellerCustomers::where('seller_id', Auth::id())
                    ->where('customer_company_id', $customer_id_val)
                    ->first();
            }
        }

        return Inertia::render('PlainBills/index', [
            'customer_list' => $customer_list,
            'company_list' => $company_list,
            'customer_data' => $customer_data,
            'inv_no' => $inv_no,
            'company' => $company_obj,
            'company_id' => $company_id_val ? (int)$company_id_val : null,
            'customer_id' => $customer_id_val ? (int)$customer_id_val : null,
            'needs_auth' => $needs_auth
        ]);
    }

    public function auth(Request $request)
    {
        $request->validate(['password' => 'required']);

        if ($request->password === 'Plain@123') {
            Session::put('plain_bill_authenticated', true);
            Session::put('plain_bill_expires_at', Carbon::now()->addHour()->toDateTimeString());
            return back()->with('success', 'Authenticated successfully.');
        }

        return back()->with('error', 'Invalid password.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoicedata.*.*' => 'required',
            'invoicedetails.0.invoice' => 'required|string',
            'invoicedetails.0.vehical_no' => 'required|string',
            'invoicedetails.0.totalWeight' => 'required|numeric',
            'invoicedetails.0.totalTaxableValue' => 'required|numeric',
        ]);

        $details = $request->invoicedetails[0];
        $customerId = $details['customer'] ?? null;
        $companyId = $details['company'] ?? null;

        // Simplified customer creation logic as per requirement "same functionality"
        if (empty($customerId) && !empty($details['name'])) {
            $customerCompany = Company::firstOrCreate(
                ['gstin' => $details['gst'] ?? ''],
                [
                    'company_name' => $details['name'],
                    'email' => $details['email'] ?? '',
                    'mobile' => $details['mobile'] ?? '',
                    'address' => $details['address'] ?? '',
                    'pin' => '000000',
                    'status' => Status::moduleStatusId('Company','registerd') ?? 1,
                    'tax_type' => 'gst',
                ]
            );

            SellerCustomers::firstOrCreate([
                'seller_id' => Auth::id(),
                'customer_company_id' => $customerCompany->id,
            ], [
                'customer_company_data' => json_encode([
                    'name' => $details['name'],
                    'email' => $details['email'] ?? '',
                    'mobile' => $details['mobile'] ?? '',
                    'gst' => $details['gst'] ?? '',
                    'address' => $details['address'] ?? ''
                ])
            ]);
            $customerId = $customerCompany->id;
        }

        $lut = CompanyLUT::where('company_id', $companyId)->latest()->first();
        
        $bill = PlainBill::create([
            'invoice_number' => $details['invoice'],
            'invoice_date' => now(),
            'payment_status' => Status::moduleStatusId('invoice_payment', 'unpaid'),
            'total_ammount' => $details['totalTaxableValue'],
            'total_weight' => $details['totalWeight'],
            'vehicle_no' => $details['vehical_no'],
            'customer_company_id' => $customerId,
            'company_id' => $companyId,
            'lut_id' => $lut ? $lut->id : null,
        ]);

        foreach ($request->invoicedata as $data) {
            PlainBillItem::create([
                'invoice_id' => $bill->id,
                'desc_product' => $data[0],
                'hsn_code' => $data[1],
                'quantity' => $data[2],
                'unit' => $data[3],
                'weight' => $data[4],
                'rate' => $data[5],
            ]);
        }

        return redirect()->route('plain-bill.list')->with('success', 'Plain Bill Created Successfully');
    }

    public function list(Request $request)
    {
        $needs_auth = $request->attributes->get('needs_plain_bill_auth', false);

        $query = PlainBill::with(['Customer', 'Company', 'payment', 'paymentStatus']);
        
        // Filter by seller (since companies/customers are linked to seller)
        $query->whereIn('company_id', Auth::user()->companies()->pluck('id'));

        if ($request->filled('customer_id')) {
            $companyId = SellerCustomers::where('id', $request->customer_id)->value('customer_company_id');
            if ($companyId) $query->where('customer_company_id', $companyId);
        }

        $invoices = $query->orderBy('created_at', 'desc')->get();
        
        // Collect filter data (reusing logic from regular invoice list)
        $customers = SellerCustomers::where('seller_id', Auth::id())
            ->get()
            ->map(function($customer) {
                $detail = json_decode($customer->customer_company_data, true);
                return [
                    'id' => $customer->id,
                    'name' => $detail['name'] ?? 'Unknown',
                ];
            });

        return Inertia::render('PlainBills/invoice_list', [
            'invoices' => $invoices,
            'customers' => $customers,
            'needs_auth' => $needs_auth
        ]);
    }

    public function template(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->invoice_id);
        } catch (DecryptException $e) {
            $id = $request->invoice_id;
        }

        $invoice = PlainBill::with(['items', 'Customer', 'lut', 'Company'])->findOrFail($id);
        return Inertia::render('PlainBills/InvoiceTemplate', ['invoice' => $invoice]);
    }

    public function edit($id)
    {
        $edit_data = PlainBill::with(['items', 'Customer'])->findOrFail($id);
        return Inertia::render('PlainBills/editbill', ['edit_data' => $edit_data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'invoicedata.*.*' => 'required',
            'invoicedetails.0.invoice' => 'required|string',
            'invoicedetails.0.vehical_no' => 'required|string',
            'invoicedetails.0.totalWeight' => 'required|numeric',
            'invoicedetails.0.totalTaxableValue' => 'required|numeric',
        ]);

        $bill = PlainBill::findOrFail($id);
        $bill->update([
            'invoice_number' => $request->invoicedetails[0]['invoice'],
            'total_ammount' => $request->invoicedetails[0]['totalTaxableValue'],
            'total_weight' => $request->invoicedetails[0]['totalWeight'],
            'vehicle_no' => $request->invoicedetails[0]['vehical_no'],
        ]);

        $bill->items()->delete();
        foreach ($request->invoicedata as $data) {
            PlainBillItem::create([
                'invoice_id' => $bill->id,
                'desc_product' => $data[0],
                'hsn_code' => $data[1],
                'quantity' => $data[2],
                'unit' => $data[3],
                'weight' => $data[4],
                'rate' => $data[5],
            ]);
        }

        return redirect()->route('plain-bill.list')->with('success', 'Plain Bill Updated Successfully');
    }
}
