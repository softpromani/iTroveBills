<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyLUT;
use App\Models\GSTInvoice;
use App\Models\GSTInvoiceItem;
use App\Models\SellerCustomers;
use App\Models\Status;
use DateTime;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class GSTInvoiceController extends Controller
{
    public function gst_index(Request $request)
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

        if ($request->filled('company_id')) {
            $company_id = is_array($request->company_id) ? $request->company_id['id'] : $request->company_id;
            $customer_id = $request->filled('customer_id') ? (is_array($request->customer_id) ? $request->customer_id['id'] : $request->customer_id) : null;

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

            $company_obj = Company::where('seller_id', Auth::id())->where('id', $company_id)->first();

            // Check latest non-expired LUT
            $latestLut = CompanyLUT::where('company_id', $company_id)
                ->where('expiry_date', '>=', $today->format('Y-m-d'))
                ->where('status', 3) // Active LUT
                ->latest()
                ->first();

            $startingNumber = 0;
            if ($latestLut && !empty($latestLut->starting_bill_count)) {
                $startingNumber = (int)$latestLut->starting_bill_count;
            }

            // Get existing GST invoice numbers for this company & financial year
            $existingInvoices = $company_obj->ThisYearGstInvoice()->pluck('invoice_number')->toArray();

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
                $billCount = $startingNumber > 0 ? $startingNumber : ($company_obj->ThisYearGstInvoice()->count() ?? 0) + 1;
            }

            $inv_no = $company_obj->invoice_series . '-' . $financialYear . '/' . str_pad($billCount, 5, '0', STR_PAD_LEFT);

            $customer_data = null;
            if ($customer_id) {
                $customer_data = SellerCustomers::where('seller_id', Auth::id())
                    ->where('customer_company_id', $customer_id)
                    ->first();
            }

            return Inertia::render('GSTBills/index', compact(
                'customer_list',
                'company_list',
                'customer_data',
                'inv_no'
            ))->with([
                'company' => $company_obj,
                'company_id' => (int)$company_id,
                'customer_id' => $customer_id ? (int)$customer_id : null
            ]);

        } else {
            return Inertia::render('GSTBills/index', compact('customer_list', 'company_list'));
        }
    }

    public function gst_index_details(Request $request)
    {
        return $this->gst_index($request);
    }

    public function gst_store(Request $request)
    {
        // Custom validation with better error messages
        $request->validate([
            'invoicedata' => 'required|array|min:1',
            'invoicedata.*.0' => 'nullable|string|max:255', // Description
            'invoicedata.*.1' => 'nullable|string|max:50',  // HSN Code
            'invoicedata.*.2' => 'nullable|numeric|min:0',  // Quantity
            'invoicedata.*.3' => 'nullable|string|max:50',  // Unit
            'invoicedata.*.4' => 'nullable|numeric|min:0',  // Weight
            'invoicedata.*.5' => 'nullable|numeric|min:0',  // Rate
            'invoicedata.*.6' => 'nullable|numeric|min:0|max:100', // GST Percentage
            'invoicedata.*.7' => 'nullable|numeric|min:0',  // GST Amount

            'invoicedetails' => 'required|array',
            'invoicedetails.0.invoice' => 'required|string|max:100',
            'invoicedetails.0.customer' => 'nullable|string',
            'invoicedetails.0.company' => 'required|string',
            'invoicedetails.0.vehical_no' => 'required|string|max:50',
            'invoicedetails.0.totalWeight' => 'required|numeric|min:0',
            'invoicedetails.0.totalTaxableValue' => 'required|numeric|min:0',
            'invoicedetails.0.totalGstAmount' => 'required|numeric|min:0',
            'invoicedetails.0.grandTotal' => 'required|numeric|min:0',
        ], [
            'invoicedata.required' => 'Invoice items are required.',
            'invoicedetails.0.invoice.required' => 'Invoice number is required.',
            'invoicedetails.0.company.required' => 'Company is required.',
            'invoicedetails.0.vehical_no.required' => 'Vehicle number is required.',
        ]);

        try {
            $details = $request->invoicedetails[0];
            $customerId = $details['customer'] ?? null;
            $companyId = $details['company'] ?? null;

            if (empty($customerId)) {
                $name = $details['customer_name'] ?? null;
                if ($name) {
                    $gst = $details['gst'] ?? null;
                    $email = $details['email'] ?? '';
                    $mobile = $details['mobile'] ?? '';
                    $address = $details['address'] ?? '';
                    
                    $customerCompany = null;
                    if ($gst) {
                        $customerCompany = Company::where('gstin', $gst)->first();
                    }
                    
                    if (!$customerCompany) {
                        $customerCompany = Company::create([
                            'company_name' => $name,
                            'email' => $email,
                            'mobile' => $mobile,
                            'gstin' => $gst,
                            'address' => $address,
                            'pin' => '000000',
                            'status' => Status::moduleStatusId('Company','registerd') ?? 1,
                            'tax_type' => 'gst',
                        ]);
                    }
                    
                    SellerCustomers::firstOrCreate([
                        'seller_id' => Auth::id(),
                        'customer_company_id' => $customerCompany->id,
                    ], [
                        'customer_company_data' => json_encode([
                            'name' => $name,
                            'email' => $email,
                            'mobile' => $mobile,
                            'gst' => $gst,
                            'address' => $address
                        ])
                    ]);
                    
                    $customerId = $customerCompany->id;
                }
            }

            // Check if invoice number already exists
            $existingInvoice = GSTInvoice::where('invoice_number', $details['invoice'])->first();
            if ($existingInvoice) {
                return back()->withErrors(['invoice' => 'Invoice number already exists.'])->withInput();
            }

            // Get LUT information
            $lut = CompanyLUT::where('company_id', $companyId)->latest()->first();

            // Filter out empty rows (where all important fields are empty/zero)
            $validInvoiceData = array_filter($request->invoicedata, function($row) {
                return !empty(trim($row[0] ?? '')) || // Description not empty
                    !empty(trim($row[1] ?? '')) || // HSN not empty
                    floatval($row[2] ?? 0) > 0 ||  // Quantity > 0
                    floatval($row[5] ?? 0) > 0;    // Rate > 0
            });

            if (empty($validInvoiceData)) {
                return back()->withErrors(['items' => 'Please add at least one valid item to the invoice.'])->withInput();
            }

            // Start database transaction
            DB::beginTransaction();

            // Create the main invoice record
            $invoice_data = GSTInvoice::create([
                'invoice_number' => $details['invoice'],
                'invoice_date' => now(),
                'payment_status' => Status::moduleStatusId('invoice_payment', 'unpaid'),
                'total_ammount' => floatval($details['totalTaxableValue']),
                'tax_amount' => floatval($details['totalGstAmount']),
                'subtotal_amount' => floatval($details['grandTotal']),
                'total_weight' => floatval($details['totalWeight']),
                'vehicle_no' => $details['vehical_no'],
                'customer_company_id' => $customerId,
                'company_id' => $companyId,
                'lut_id' => $lut ? $lut->id : null,
            ]);

            // Create invoice items with proper validation and calculations
            foreach ($validInvoiceData as $key => $data) {
                // Ensure all numeric fields are properly converted
                $quantity = floatval($data[2] ?? 0);
                $rate = floatval($data[5] ?? 0);
                $gst_percentage = floatval($data[6] ?? 0);
                $gst_amount = floatval($data[7] ?? 0);
                $weight = floatval($data[4] ?? 0);

                // Calculate taxable value (quantity * rate)
                $taxable_value = $quantity * $rate;

                // Validate GST calculations (optional - for data integrity)
                $calculated_gst_amount = ($taxable_value * $gst_percentage) / 100;
                if (abs($calculated_gst_amount - $gst_amount) > 0.01) {
                    // If there's a significant difference, use calculated value
                    $gst_amount = $calculated_gst_amount;
                }

                // Calculate subtotal (taxable value + GST amount)
                $subtotal_amount = $taxable_value + $gst_amount;

                GSTInvoiceItem::create([
                    'invoice_id' => $invoice_data->id,
                    'desc_product' => trim($data[0] ?? ''),
                    'hsn_code' => trim($data[1] ?? ''),
                    'quantity' => $quantity,
                    'unit' => trim($data[3] ?? ''),
                    'weight' => $weight,
                    'rate' => $rate,
                    'gst_percentage' => $gst_percentage,
                    'gst_amount' => round($gst_amount, 2),
                    'subtotal_amount' => round($subtotal_amount, 2),
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('gst.invoice.list')
                ->with('success', 'GST Invoice Created Successfully with Invoice Number: ' . $request->invoicedetails[0]['invoice']);

        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();

            // Log the error for debugging
            Log::error('GST Invoice Creation Error: ' . $e->getMessage());

            return back()->withErrors(['error' => 'An error occurred while creating the invoice. Please try again.'])
                    ->withInput();
        }
    }

    public function gst_edit(Request $request)
    {
        try {
            $edit_data = GSTInvoice::with(['invoiceitems', 'Customer', 'Company'])->findOrFail($request->invoice_id);
            return Inertia::render('GSTBills/editbill', compact('edit_data'));

        } catch (\Exception $e) {
            return redirect()->route('gst.invoice.list')
                ->with('error', 'Invoice not found or access denied.');
        }
    }

    /**
     * Update the specified GST invoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $invoice_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function gst_update(Request $request, $invoice_id)
    {
        // Enhanced validation
        $request->validate([
            'invoicedata' => 'required|array|min:1',
            'invoicedata.*.0' => 'nullable|string|max:255', // Description
            'invoicedata.*.1' => 'nullable|string|max:50',  // HSN Code
            'invoicedata.*.2' => 'nullable|numeric|min:0',  // Quantity
            'invoicedata.*.3' => 'nullable|string|max:50',  // Unit
            'invoicedata.*.4' => 'nullable|numeric|min:0',  // Weight
            'invoicedata.*.5' => 'nullable|numeric|min:0',  // Rate
            'invoicedata.*.6' => 'nullable|numeric|min:0|max:100', // GST Percentage
            'invoicedata.*.7' => 'nullable|numeric|min:0',  // GST Amount

            'invoicedetails' => 'required|array',
            'invoicedetails.0.invoice' => 'required|string|max:100',
            'invoicedetails.0.customer' => 'required',
            'invoicedetails.0.company' => 'required',
            'invoicedetails.0.vehical_no' => 'required|string|max:50',
            'invoicedetails.0.totalWeight' => 'required|numeric|min:0',
            'invoicedetails.0.totalTaxableValue' => 'required|numeric|min:0',
            'invoicedetails.0.totalGstAmount' => 'required|numeric|min:0',
            'invoicedetails.0.grandTotal' => 'required|numeric|min:0',
        ], [
            'invoicedata.required' => 'Invoice items are required.',
            'invoicedata.min' => 'At least one invoice item is required.',
            'invoicedetails.0.invoice.required' => 'Invoice number is required.',
            'invoicedetails.0.customer.required' => 'Customer is required.',
            'invoicedetails.0.company.required' => 'Company is required.',
            'invoicedetails.0.vehical_no.required' => 'Vehicle number is required.',
        ]);

        try {
            // Find the invoice to update, or fail if not found
            $invoice = GSTInvoice::findOrFail($invoice_id);

            // Check if invoice number already exists for other invoices
            $existingInvoice = GSTInvoice::where('invoice_number', $request->invoicedetails[0]['invoice'])
                                        ->where('id', '!=', $invoice_id)
                                        ->first();
            if ($existingInvoice) {
                return back()->withErrors(['invoice' => 'Invoice number already exists for another invoice.'])->withInput();
            }

            // Get LUT information
            $lut = CompanyLUT::where('company_id', $request->invoicedetails[0]['company'])->latest()->first();

            // Filter out empty rows (where all important fields are empty/zero)
            $validInvoiceData = array_filter($request->invoicedata, function ($row) {
                return !empty(trim($row[0] ?? '')) || // Description not empty
                    !empty(trim($row[1] ?? '')) || // HSN not empty
                    floatval($row[2] ?? 0) > 0 ||  // Quantity > 0
                    floatval($row[5] ?? 0) > 0;    // Rate > 0
            });

            if (empty($validInvoiceData)) {
                return back()->withErrors(['items' => 'Please add at least one valid item to the invoice.'])->withInput();
            }

            // Start database transaction
            DB::beginTransaction();

            // Update the main invoice record
            $invoice->update([
                'invoice_number' => $request->invoicedetails[0]['invoice'],
                'total_ammount' => floatval($request->invoicedetails[0]['totalTaxableValue']),
                'tax_amount' => floatval($request->invoicedetails[0]['totalGstAmount']),
                'subtotal_amount' => floatval($request->invoicedetails[0]['grandTotal']),
                'total_weight' => floatval($request->invoicedetails[0]['totalWeight']),
                'vehicle_no' => $request->invoicedetails[0]['vehical_no'],
                'customer_company_id' => $request->invoicedetails[0]['customer'],
                'company_id' => $request->invoicedetails[0]['company'],
                'lut_id' => $lut ? $lut->id : null,
                'updated_at' => now(),
            ]);

            // Delete existing invoice items (this will be handled by the model's boot method)
            // But let's be explicit about it
            GSTInvoiceItem::where('invoice_id', $invoice->id)->delete();

            // Create new invoice items with proper validation and calculations
            foreach ($validInvoiceData as $key => $data) {
                // Ensure all numeric fields are properly converted
                $quantity = floatval($data[2] ?? 0);
                $rate = floatval($data[5] ?? 0);
                $gst_percentage = floatval($data[6] ?? 0);
                $gst_amount = floatval($data[7] ?? 0);
                $weight = floatval($data[4] ?? 0);

                // Calculate taxable value (quantity * rate)
                $taxable_value = $quantity * $rate;

                // Validate GST calculations (optional - for data integrity)
                $calculated_gst_amount = ($taxable_value * $gst_percentage) / 100;
                if (abs($calculated_gst_amount - $gst_amount) > 0.01) {
                    // If there's a significant difference, use calculated value
                    $gst_amount = $calculated_gst_amount;
                }

                // Calculate subtotal (taxable value + GST amount)
                $subtotal_amount = $taxable_value + $gst_amount;

                GSTInvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'desc_product' => trim($data[0] ?? ''),
                    'hsn_code' => trim($data[1] ?? ''),
                    'quantity' => $quantity,
                    'unit' => trim($data[3] ?? ''),
                    'weight' => $weight,
                    'rate' => $rate,
                    'gst_percentage' => $gst_percentage,
                    'gst_amount' => round($gst_amount, 2),
                    'subtotal_amount' => round($subtotal_amount, 2),
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('gst.invoice.list')
                ->with('success', 'GST Invoice Updated Successfully with Invoice Number: ' . $request->invoicedetails[0]['invoice']);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            return redirect()->route('gst.invoice.list')
                ->with('error', 'Invoice not found.');

        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();

            // Log the error for debugging
            Log::error('GST Invoice Update Error: ' . $e->getMessage(), [
                'invoice_id' => $invoice_id,
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'An error occurred while updating the invoice. Please try again.'])
                ->withInput();
        }
    }

    public function gst_invoice_list()
    {
        $invoices = auth()->user()->gst_invoices()->orderBy('invoice_date', 'desc')->get();
        // $invoices->load('paymentStatus');
        $invoices->load('Customer');
        $invoices->load('Company');
        // $invoices->load('payment');
        return inertia('gst-invoices/invoice_list', compact('invoices'));
    }

    public function gst_template(Request $request)
    {
        try {
            $inv_id = Crypt::decrypt($request->invoice_id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $inv_id = $request->invoice_id; // fallback if already plain ID
        }

        $invoice = GSTInvoice::with(['invoiceitems', 'Customer', 'Company', 'lut'])
            ->findOrFail($inv_id);

        return inertia('gst-invoices/InvoiceTemplate', compact('invoice'));
    }


}
