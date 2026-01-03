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
        if (!$invoice_id) {
            return back()->with('error', 'Invoice ID is required');
        }

        // Try to find in regular Invoice or GSTInvoice
        $invoice = GSTInvoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
        $type = 'gst';

        if (!$invoice) {
            $invoice = Invoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
            $type = 'regular';
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
        $invoice = GSTInvoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
        
        if (!$invoice) {
            $invoice = Invoice::with(['Company', 'Customer', 'invoiceitems'])->find($invoice_id);
        }

        if ($invoice) {
            $invoice->items = $invoice->invoiceitems;
        }

        if (!$invoice) {
            return response()->json(['status' => 0, 'message' => 'Invoice not found'], 404);
        }

        $fromGstin = $invoice->Company->gstin;
        $toGstin = $invoice->Customer->gstin;

        $fromStateCode = (int)substr($fromGstin, 0, 2);
        $toStateCode = (int)substr($toGstin, 0, 2);
        
        $isInterState = ($fromStateCode !== $toStateCode);

        // Prepare Item List
        $itemList = [];
        $totalCgst = 0;
        $totalSgst = 0;
        $totalIgst = 0;

        foreach ($invoice->items as $item) {
            $taxableAmount = (float)($item->subtotal_amount ?? ($item->rate * $item->quantity));
            $gstRate = (float)($item->gst_percentage ?? 0);
            
            $cgstRate = 0;
            $sgstRate = 0;
            $igstRate = 0;

            if ($isInterState) {
                $igstRate = $gstRate;
                $totalIgst += ($taxableAmount * $igstRate / 100);
            } else {
                $cgstRate = $gstRate / 2;
                $sgstRate = $gstRate / 2;
                $totalCgst += ($taxableAmount * $cgstRate / 100);
                $totalSgst += ($taxableAmount * $sgstRate / 100);
            }

            $itemList[] = [
                "productName" => $item->desc_product,
                "productDesc" => $item->desc_product,
                "hsnCode" => (int)preg_replace('/[^0-9]/', '', $item->hsn_code),
                "quantity" => (float)$item->quantity,
                "qtyUnit" => $item->unit ?? 'BOX',
                "taxableAmount" => $taxableAmount,
                "sgstRate" => $sgstRate,
                "cgstRate" => $cgstRate,
                "igstRate" => $igstRate,
                "cessRate" => 0
            ];
        }

        $data = [
            "supplyType" => $request->supplyType,
            "subSupplyType" => $request->subSupplyType,
            "subSupplyDesc" => $request->subSupplyDesc ?? "",
            "docType" => "INV",
            "docNo" => $invoice->invoice_number,
            "docDate" => date('d/m/Y', strtotime($invoice->invoice_date)),
            "fromGstin" => $fromGstin,
            "fromTrdName" => $invoice->Company->company_name,
            "fromAddr1" => $invoice->Company->address,
            "fromAddr2" => "",
            "fromPlace" => $invoice->Company->city ?? "Default Place",
            "actFromStateCode" => $fromStateCode,
            "fromPincode" => (int)$invoice->Company->pin,
            "fromStateCode" => $fromStateCode,
            "toGstin" => $toGstin,
            "toTrdName" => $invoice->Customer->company_name,
            "toAddr1" => $invoice->Customer->address,
            "toAddr2" => "",
            "toPlace" => $invoice->Customer->city ?? "Default Place",
            "toPincode" => (int)$invoice->Customer->pin,
            "actToStateCode" => $toStateCode,
            "toStateCode" => $toStateCode,
            "transactionType" => (int)$request->transactionType ?? 1,
            "totalValue" => (float)$invoice->total_ammount,
            "cgstValue" => round($totalCgst, 2),
            "sgstValue" => round($totalSgst, 2),
            "igstValue" => round($totalIgst, 2),
            "cessValue" => 0,
            "totInvValue" => round((float)$invoice->total_ammount + $totalCgst + $totalSgst + $totalIgst, 2),
            "transMode" => $request->transMode,
            "transDistance" => $request->transDistance,
            "transporterName" => $request->transporterName ?? "",
            "transporterId" => $request->transporterId ?? "",
            "transDocNo" => $request->transDocNo ?? "",
            "transDocDate" => $request->transDocDate ?? "",
            "vehicleNo" => $request->vehicleNo,
            "vehicleType" => $request->vehicleType ?? "R",
            "itemList" => $itemList
        ];

        try {
            $config = config('gst_portal');
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'ip_address' => $config['ip_address'],
                'client_id' => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'gstin' => $invoice->Company->gstin,
            ])->post($config['gst_base_url'] . '/ewayapi/v1.03/ewayapi/genewaybill?email=' . urlencode($config['email']), $data);

            $responseData = $response->json();

            if (isset($responseData['status_cd']) && $responseData['status_cd'] == "1") {
                // Store in EwayBill table
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
                return response()->json(['status' => 0, 'message' => $responseData['error']['message'] ?? 'Failed to generate E-Way Bill'], 400);
            }

        } catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
