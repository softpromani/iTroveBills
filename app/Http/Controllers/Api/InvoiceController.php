<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    function fetchInvoice($invoice_id)
    {
        $data = Invoice::find($invoice_id);
        if($data)
        {
            return $data;
        }
        else
        {
            return Null;
        }
    }

    function updateInvoicePackage(Request $request)
    {
        $data = Invoice::find($request->invoice_id);
        if($data)
        {
            $update = Invoice::where('id', $request->invoice_id)->update([
                'vehicle_no' => $request->vehicle_no,
                'no_packets' => $request->no_packets
            ]);

            return response()->json(['status' => 1]);
        }
        else{
            return response()->json(['status' => 0]);
        }
    }
}
