<?php

namespace App\Services;

use App\Models\EwayBill;
use App\Models\MasterState;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EwayBillService
{
    protected $ip_address;
    protected $gst_portal_client_id;
    protected $gst_portal_client_secret;
    protected $gst_portal_gstin;
    protected $gst_portal_email;
    protected $gst_portal_username;
    protected $gst_portal_password;
    protected $gst_supervisor_id;
    protected $gst_base_url;

    public function __construct()
    {
        $this->setEWBCredential();
    }

    protected function setEWBCredential()
    {
        $this->ip_address              = config('gst_portal.ip_address');
        $this->gst_portal_client_id    = config('gst_portal.client_id');
        $this->gst_portal_client_secret= config('gst_portal.client_secret');
        $this->gst_portal_gstin        = config('gst_portal.gstin');
        $this->gst_portal_email        = config('gst_portal.email');
        $this->gst_portal_username     = config('gst_portal.username');
        $this->gst_portal_password     = config('gst_portal.password');
        $this->gst_supervisor_id       = config('gst_portal.supervisor_id');
        $this->gst_base_url            = rtrim(config('gst_portal.gst_base_url'), '/');
    }

    protected function executeHTTP($endpoint, $query = [])
    {
        $response = Http::withHeaders([
            'ip_address'    => $this->ip_address,
            'client_id'     => $this->gst_portal_client_id,
            'client_secret' => $this->gst_portal_client_secret,
            'gstin'         => $this->gst_portal_gstin,
        ])->get($this->gst_base_url . $endpoint, $query);

        return $response->json();
    }

    public function authenticate()
    {
        return $this->executeHTTP('/authenticate', [
            'email'    => $this->gst_portal_email,
            'username' => $this->gst_portal_username,
            'password' => $this->gst_portal_password,
        ]);
    }

    public function fetchEWBList($date)
    {
        return $this->executeHTTP('/ewayapi/getewaybillsfortransporter', [
            'email' => $this->gst_portal_email,
            'date'  => $date,
        ]);
    }

    public function fetchEWBDetails($ewbNo)
    {
        return $this->executeHTTP('/ewayapi/getewaybill', [
            'email' => $this->gst_portal_email,
            'ewbNo' => $ewbNo,
        ]);
    }

    public function processEWBListData($dates = [])
    {
        $this->authenticate();

        $ewb_list = [];
        foreach ($dates as $date) {
            $response_data = $this->fetchEWBList($date);
            if (!isset($response_data['data']) || $response_data['status_cd'] == 0) {
                continue;
            }
            foreach ($response_data['data'] as $v) {
                $ewb_list[] = $v['ewbNo'];
            }
        }

        if (empty($ewb_list)) {
            return ['error_msg' => 'No data found from eway.'];
        }

        // Filter already stored
        $ewb_in_db = EwayBill::query()->whereIn('ewb_eway_bill_no', $ewb_list)->pluck('ewb_eway_bill_no');
        $new_ewb_list = array_diff($ewb_list, $ewb_in_db->toArray());

        $insert_array = [];
        foreach ($new_ewb_list as $ewbNo) {
            $all_ewb_details = $this->fetchEWBDetails($ewbNo);
            if (!$all_ewb_details || empty($all_ewb_details['data'])) continue;

            $data = $all_ewb_details['data'];

            $total_quantity = collect($data['itemList'])->sum('quantity');
            $state = MasterState::query()->where('state_code', $data['toStateCode'])->pluck('name')->first();

            $insert_array[] = [
                'status'           => 'active',
                'supervisor_id'    => $this->gst_supervisor_id,
                'ewb_eway_bill_no' => $data['ewbNo'],
                'ewb_invoice_no'   => $data['docNo'] ?? '',
                'ewb_from'         => trim("{$data['fromAddr1']} {$data['fromAddr2']} {$data['fromPlace']} {$data['fromPincode']} {$data['fromStateCode']}"),
                'ewb_to'           => "{$data['toAddr1']} {$data['toAddr2']}",
                'ewb_to_city'      => $data['toPlace'],
                'ewb_to_state_code'=> $data['toStateCode'],
                'ewb_to_pincode'   => $data['toPincode'],
                'state'            => $state,
                'ewb_quantity'     => $total_quantity,
                'ewb_grn_no'       => $data['VehiclListDetails'][0]['transDocNo'] ?? '',
                'ewb_created_at'   => date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $data['ewayBillDate']))),
                'ewb_vehicle_no'   => $data['VehiclListDetails'][0]['vehicleNo'] ?? rand(1000000, 9999999),
                'json_data'        => json_encode($data),
                'created_at'       => now(),
            ];
        }

        if (!empty($insert_array)) {
            EwayBill::insert($insert_array);
            return ['success_msg' => 'Data inserted successfully.'];
        }

        return ['error_msg' => 'No new data to insert.'];
    }

    public function dateRange($StartDate = '', $EndDate = '', $Steps = '+1 day', $Format = 'Y-m-d')
    {
        $Dates = [];
        $Current = strtotime($StartDate);
        $Last = strtotime($EndDate);
        while ($Current <= $Last) {
            $Dates[] = date($Format, $Current);
            $Current = strtotime($Steps, $Current);
        }
        return $Dates;
    }

    public function refreshEWB(Request $request)
    {
        try {
            $from_date = $request->from ? date('d-m-Y', strtotime($request->from)) : date('d-m-Y');
            $to_date   = $request->to   ? date('d-m-Y', strtotime($request->to))   : date('d-m-Y');

            $dates = $this->dateRange($from_date, $to_date, '+1 day', 'd/m/Y');
            $response = $this->processEWBListData($dates);

            if (!empty($response['error_msg'])) {
                throw new Exception($response['error_msg']);
            }
            return back()->with('success', $response['success_msg']);
        } catch (Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
