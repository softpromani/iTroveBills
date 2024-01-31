<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class MastergstController extends Controller
{
    public function master_gst_auth()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'ip_address' => '66.249.75.99',
            'client_id' => 'f596e958-7675-428f-b102-783fb191fabe',
            'client_secret' => '37764526-c022-4116-b8d8-73ff4c3900b6',
            'gstin' => '05AAACH6188F1ZM',
        ])->get('https://api.mastergst.com/ewaybillapi/v1.03/authenticate', [
            'email' => 'kshivambaranwal@gmail.com',
            'username' => '05AAACH6188F1ZM',
            'password' => 'abc123@@',
        ]);

        // Access response data
        $responseData = $response->json();

    }

    public function create_eway_bill(){
        $data = [
            "supplyType" => "O",
            "subSupplyType" => "1",
            "subSupplyDesc" => " ",
            "docType" => "INV",
            "docNo" => "ebill/06/2020",
            "docDate" => "05/02/2020",
            "fromGstin" => "05AAACH6188F1ZM",
            "fromTrdName" => "welton",
            "fromAddr1" => "2ND CROSS NO 59  19  A",
            "fromAddr2" => "GROUND FLOOR OSBORNE ROAD",
            "fromPlace" => "FRAZER TOWN",
            "actFromStateCode" => 5,
            "fromPincode" => 263652,
            "fromStateCode" => 5,
            "toGstin" => "02EHFPS5910D2Z0",
            "toTrdName" => "sthuthya",
            "toAddr1" => "Shree Nilaya",
            "toAddr2" => "Dasarahosahalli",
            "toPlace" => "Beml Nagar",
            "toPincode" => 176036,
            "actToStateCode" => 2,
            "toStateCode" => 2,
            "transactionType" => 4,
            "dispatchFromGSTIN" => "29AAAAA1303P1ZV",
            "dispatchFromTradeName" => "ABC Traders",
            "shipToGSTIN" => "29ALSPR1722R1Z3",
            "shipToTradeName" => "XYZ Traders",
            "totalValue" => 56099,
            "cgstValue" => 0,
            "sgstValue" => 0,
            "igstValue" => 300.67,
            "cessValue" => 400.56,
            "cessNonAdvolValue" => 400,
            "totInvValue" => 68358,
            "transMode" => "1",
            "transDistance" => "656",
            "transporterName" => "",
            "transporterId" => "05AAACG0904A1ZL",
            "transDocNo" => "12",
            "transDocDate" => "",
            "vehicleNo" => "APR3214",
            "vehicleType" => "R",
            "itemList" => [
                [
                    "productName" => "Wheat",
                    "productDesc" => "Wheat",
                    "hsnCode" => 1001,
                    "quantity" => 4,
                    "qtyUnit" => "BOX",
                    "taxableAmount" => 56099,
                    "sgstRate" => 0,
                    "cgstRate" => 0,
                    "igstRate" => 3,
                    "cessRate" => 0
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'ip_address' => '66.249.75.99',
            'client_id' => 'f596e958-7675-428f-b102-783fb191fabe',
            'client_secret' => '37764526-c022-4116-b8d8-73ff4c3900b6',
            'gstin' => '05AAACH6188F1ZM',
        ])->post('https://api.mastergst.com/ewaybillapi/v1.03/ewayapi/genewaybill?email=kshivambaranwal%40gmail.com', $data);

        // Access response data
        $responseData = $response->json();
    }
}
