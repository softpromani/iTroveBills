<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    function fetchInvoice($invoice_id, $type = 'regular')
    {
        if ($type === 'gst') {
            $data = \App\Models\GSTInvoice::find($invoice_id);
        } elseif ($type === 'proforma') {
            $data = \App\Models\PerformaInvoice::find($invoice_id);
        } else {
            $data = Invoice::find($invoice_id);
        }

        return $data;
    }

    function updateInvoicePackage(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $type = $request->type ?? 'regular';

        if ($type === 'gst') {
            $model = \App\Models\GSTInvoice::class;
        } elseif ($type === 'proforma') {
            $model = \App\Models\PerformaInvoice::class;
        } else {
            $model = Invoice::class;
        }

        $data = $model::find($invoice_id);

        if($data)
        {
            $model::where('id', $invoice_id)->update([
                'vehicle_no' => $request->vehicle_no,
                'no_packets' => $request->no_packets,
                'dispatched_through' => $request->dispatched_through
            ]);

            return response()->json(['status' => 1]);
        }
        else{
            return response()->json(['status' => 0]);
        }
    }
}
