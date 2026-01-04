<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\GSTInvoice;
use App\Models\EwayBill;
use App\Models\EwayBillItem;
use App\Models\Company;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class MastergstController extends Controller
{
    public function create_eway_bill(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $type = $request->type; // 'regular', 'gst', 'performa'

        if (!$invoice_id) {
            return back()->with('error', 'Invoice ID is required');
        }

        // Try to decrypt if it's encrypted
        try {
            $invoice_id = \Illuminate\Support\Facades\Crypt::decrypt($invoice_id);
        } catch (\Exception $e) {
            // Probably already plain ID
        }

        $invoice = null;

        if ($type === 'gst') {
            $invoice = GSTInvoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
        } elseif ($type === 'performa') {
            $invoice = \App\Models\PerformaInvoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
        } elseif ($type === 'regular') {
            $invoice = Invoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
        } else {
            // Fallback: Try to find in regular Invoice or GSTInvoice (Original logic)
            $invoice = GSTInvoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
            $type = 'gst';

            if (!$invoice) {
                $invoice = Invoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
                $type = 'regular';
            }
        }

        if ($invoice) {
            // Normalize items for the view
            $invoice->items = $invoice->invoiceitems;
        }

        if (!$invoice) {
            return back()->with('error', 'Invoice not found');
        }

        return Inertia::render('invoices/CreateEwayBill', [
            'invoice' => $invoice,
            'invoice_type' => $type
        ]);
    }

    public function generate_eway_bill(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required',
            'transDistance' => 'required|numeric',
            'transMode' => 'required',
            'vehicleNo' => 'required_if:transMode,1,2,3',
            'supplyType' => 'required',
            'subSupplyType' => 'required',
        ]);

        $invoice_id = $request->invoice_id;
        $type = $request->invoice_type ?? $request->type; // 'regular', 'gst', 'performa'

        // Try to decrypt if it's encrypted
        try {
            $invoice_id = \Illuminate\Support\Facades\Crypt::decrypt($invoice_id);
        } catch (\Exception $e) {
            // Probably already plain ID
        }

        $invoice = null;

        if ($type === 'gst') {
            $invoice = GSTInvoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
        } elseif ($type === 'performa') {
            $invoice = \App\Models\PerformaInvoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
        } elseif ($type === 'regular') {
            $invoice = Invoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
        } else {
            // Fallback
            $invoice = GSTInvoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
            if (!$invoice) {
                $invoice = Invoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
            }
        }
        
        if ($invoice) {
            $invoice->items = $invoice->invoiceitems;
        }

        if (!$invoice) {
            return response()->json(['status' => 0, 'message' => 'Invoice not found'], 404);
        }

        $fromGstin = $invoice->Company->gstin;
        $toGstin = $invoice->Customer->gstin;

        // Strict GSTIN Validation before proceeding
        if (strlen($fromGstin) !== 15) {
            return response()->json(['status' => 0, 'message' => "Invalid Seller GSTIN ($fromGstin). A valid GSTIN must be exactly 15 characters. Dummy data like 'HGH767' will be rejected by the government portal."], 400);
        }
        if (strlen($toGstin) !== 15) {
            return response()->json(['status' => 0, 'message' => "Invalid Buyer GSTIN ($toGstin). A valid GSTIN must be exactly 15 characters or 'URP' for unregistered consumers."], 400);
        }

        $fromStateCode = (int)substr($fromGstin, 0, 2);
        $toStateCode = (int)substr($toGstin, 0, 2);
        
        if ($fromStateCode === 0 || $toStateCode === 0) {
            return response()->json(['status' => 0, 'message' => "Invalid State Code derived from GSTIN. The first 2 digits of the GSTIN must be a valid state code (01-37)."], 400);
        }

        $isInterState = ($fromStateCode !== $toStateCode);

        // 1. Prepare and Filter Item List first
        $itemList = [];
        $calculatedTotalTaxableValue = 0;
        $calculatedTotalCgst = 0;
        $calculatedTotalSgst = 0;
        $calculatedTotalIgst = 0;

        foreach ($invoice->items as $item) {
            $taxableAmount = round((float)($item->subtotal_amount ?? ($item->rate * $item->quantity)), 2);
            
            // Skip zero-value items as they are not allowed in E-Way Bill
            if ($taxableAmount <= 0) continue;

            $gstRate = (float)($item->gst_percentage ?? 0);
            $cgstRate = 0;
            $sgstRate = 0;
            $igstRate = 0;

            if ($isInterState) {
                $igstRate = $gstRate;
                $itemIgst = round(($taxableAmount * $igstRate / 100), 2);
                $calculatedTotalIgst += $itemIgst;
            } else {
                $cgstRate = $gstRate / 2;
                $sgstRate = $gstRate / 2;
                $itemCgst = round(($taxableAmount * $cgstRate / 100), 2);
                $itemSgst = round(($taxableAmount * $sgstRate / 100), 2);
                $calculatedTotalCgst += $itemCgst;
                $calculatedTotalSgst += $itemSgst;
            }

            $calculatedTotalTaxableValue += $taxableAmount;

            // Unit Code must be exactly 3 chars as per NIC
            $qtyUnit = strtoupper(trim($item->unit ?: 'NOS'));
            if (strlen($qtyUnit) > 3) $qtyUnit = substr($qtyUnit, 0, 3);
            if (strlen($qtyUnit) < 3) $qtyUnit = str_pad($qtyUnit, 3, 'S'); // e.g. PC -> PCS

            $itemList[] = [
                "productName" => substr(strip_tags($item->desc_product), 0, 100), 
                "productDesc" => substr(strip_tags($item->desc_product), 0, 100),
                "hsnCode" => (int)preg_replace('/[^0-9]/', '', $item->hsn_code),
                "quantity" => round((float)$item->quantity, 2),
                "qtyUnit" => $qtyUnit,
                "taxableAmount" => round($taxableAmount, 2),
                "sgstRate" => round($sgstRate, 2),
                "cgstRate" => round($cgstRate, 2),
                "igstRate" => round($igstRate, 2),
                "cessRate" => 0
            ];
        }

        if (empty($itemList)) {
            return response()->json(['status' => 0, 'message' => 'No items with a value greater than 0 were found. E-Way Bill requires at least one taxable item.'], 400);
        }

        // 2. Document Number Sanitization (NIC: Max 16 chars, only / and - allowed)
        $docNo = $invoice->invoice_number;
        $docNo = preg_replace('/[^A-Za-z0-9\/\-]/', '', $docNo); // Only alphanumeric, / and -
        if (strlen($docNo) > 16) {
            // If it's too long, we try to take the last 16 chars as it usually contains the unique serial
            $docNo = substr($docNo, -16);
        }

        // 3. Validate Distance
        $transDistance = (int)$request->transDistance;
        if ($transDistance > 4000) {
            return response()->json(['status' => 0, 'message' => 'Distance cannot exceed 4000 KM according to E-Way Bill rules.'], 400);
        }

        // 4. Handle Transporter ID (Must be 15 chars if provided)
        $transporterId = preg_replace('/[^A-Za-z0-9]/', '', $request->transporterId);
        if ($transporterId && strlen($transporterId) !== 15) {
            $transporterId = ""; 
        }

        // 5. Build Final Data with Strict Pincode and HSN validation (Allowing overrides from form)
        $fromPincode = preg_replace('/[^0-9]/', '', $request->fromPincode ?: $invoice->Company->pin);
        $toPincode = preg_replace('/[^0-9]/', '', $request->toPincode ?: $invoice->Customer->pin);

        if (strlen($fromPincode) !== 6) {
            return response()->json(['status' => 0, 'message' => 'Invalid Seller Pincode ('.$fromPincode.'). It must be exactly 6 digits.'], 400);
        }
        if (strlen($toPincode) !== 6) {
            return response()->json(['status' => 0, 'message' => 'Invalid Buyer Pincode ('.$toPincode.'). It must be exactly 6 digits.'], 400);
        }

        $data = [
            "supplyType" => $request->supplyType,
            "subSupplyType" => $request->subSupplyType,
            "subSupplyDesc" => substr($request->subSupplyDesc ?? "", 0, 20),
            "docType" => "INV",
            "docNo" => $docNo,
            "docDate" => date('d/m/Y', strtotime($invoice->invoice_date)),
            "fromGstin" => $fromGstin,
            "fromTrdName" => substr($invoice->Company->company_name, 0, 100),
            "fromAddr1" => substr($invoice->Company->address, 0, 100),
            "fromAddr2" => "",
            "fromPlace" => substr($invoice->Company->city ?: "Default Place", 0, 50),
            "actFromStateCode" => $fromStateCode,
            "fromPincode" => (int)$fromPincode, // Send as int to NIC, but validate as string
            "fromStateCode" => $fromStateCode,
            "toGstin" => $toGstin,
            "toTrdName" => substr($invoice->Customer->company_name, 0, 100),
            "toAddr1" => substr($invoice->Customer->address, 0, 100),
            "toAddr2" => "",
            "toPlace" => substr($invoice->Customer->city ?: "Default Place", 0, 50),
            "toPincode" => (int)$toPincode, // Send as int to NIC
            "actToStateCode" => $toStateCode,
            "toStateCode" => $toStateCode,
            "transactionType" => (int)($request->transactionType ?? 1),
            "totalValue" => round($calculatedTotalTaxableValue, 2),
            "cgstValue" => round($calculatedTotalCgst, 2),
            "sgstValue" => round($calculatedTotalSgst, 2),
            "igstValue" => round($calculatedTotalIgst, 2),
            "cessValue" => 0,
            "totInvValue" => round($calculatedTotalTaxableValue + $calculatedTotalCgst + $calculatedTotalSgst + $calculatedTotalIgst, 2),
            "transMode" => (int)$request->transMode,
            "transDistance" => $transDistance,
            "transporterName" => substr($request->transporterName ?? "", 0, 100),
            "transporterId" => $transporterId,
            "transDocNo" => substr($request->transDocNo ?? "", 0, 15),
            "transDocDate" => $request->transDocDate ?? "",
            "vehicleNo" => strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $request->vehicleNo)),
            "vehicleType" => $request->vehicleType ?? "R",
            "itemList" => $itemList,
            "otherValue" => 0 
        ];

        try {
            $config = \App\Models\MasterGstConfig::where('company_id', $invoice->company_id)->first();
            
            if (!$config) {
                return response()->json(['status' => 0, 'message' => 'E-Way Bill settings not found for this company. Please configure them in Firm -> E-Way Bill Setup.'], 400);
            }

            if (!$config->gst_base_url || !filter_var($config->gst_base_url, FILTER_VALIDATE_URL) || !str_starts_with($config->gst_base_url, 'http')) {
                return response()->json(['status' => 0, 'message' => 'Invalid Base URL in E-Way Bill settings. Please ensure it is a full URL starting with https:// in Firm -> E-Way Bill Setup.'], 400);
            }

            $apiUrl = rtrim($config->gst_base_url, '/') . '/ewayapi/v1.03/ewayapi/genewaybill?email=' . urlencode($config->email);
            
            // Log the request for debugging
            Log::info('MasterGST E-Way Bill Request:', [
                'url' => $apiUrl,
                'data' => $data,
                'headers' => [
                    'ip_address' => $config->ip_address,
                    'gstin' => $config->gstin ?? $invoice->Company->gstin
                ]
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'ip_address' => $config->ip_address,
                'client_id' => $config->client_id,
                'client_secret' => $config->client_secret,
                'gstin' => $config->gstin ?? $invoice->Company->gstin,
            ])->post($apiUrl, $data);

            $responseData = $response->json();
            
            // Log the response
            Log::info('MasterGST E-Way Bill Response:', ['response' => $responseData]);

            if (isset($responseData['status_cd']) && $responseData['status_cd'] == "1") {
                // ... same storage logic ...
                DB::transaction(function() use ($invoice, $responseData, $itemList) {
                    $ewb = EwayBill::create([
                        'status' => 'active',
                        'doc_no' => $invoice->invoice_number,
                        'doc_date' => $invoice->invoice_date,
                        'ewb_eway_bill_no' => $responseData['data']['ewayBillNo'],
                        'ewb_created_at' => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $responseData['data']['ewayBillDate']))),
                        'json_data' => json_encode($responseData)
                    ]);

                    foreach ($itemList as $item) {
                        EwayBillItem::create([
                            'eway_bill_id' => $ewb->id,
                            'product_name' => $item['productName'],
                            'hsn_code' => $item['hsnCode'],
                            'quantity' => $item['quantity'],
                            'qty_unit' => $item['qtyUnit'],
                            'taxable_amount' => $item['taxableAmount'],
                            'cgst_rate' => $item['cgstRate'],
                            'sgst_rate' => $item['sgstRate'],
                            'igst_rate' => $item['igstRate'],
                        ]);
                    }
                });

                return response()->json(['status' => 1, 'message' => 'E-Way Bill Generated Successfully: ' . $responseData['data']['ewayBillNo']]);
            } else {
                return response()->json([
                    'status' => 0, 
                    'message' => $responseData['error']['message'] ?? 'Failed to generate E-Way Bill',
                    'details' => $responseData // Include details for the user to see if possible
                ], 400);
            }

        } catch (Exception $e) {
            Log::error('MasterGST exception:', ['message' => $e->getMessage()]);
            return response()->json(['status' => 0, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
