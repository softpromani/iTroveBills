<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\SellerCustomers;
use App\Models\Party;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class LedgerReportController extends Controller
{
    public function index()
    {
        $customers = SellerCustomers::where('seller_id', Auth::id())->get();
        $parties = Party::where('user_id', Auth::id())->get();
        return Inertia::render('Ledger/Report', [
            'customers' => $customers,
            'parties' => $parties,
            'filters' => [
                'seller_customer_id' => '',
                'party_id' => '',
                'start_date' => '',
                'end_date' => '',
            ],
            'report_data' => null
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'seller_customer_id' => 'nullable|exists:seller_customers,id',
            'party_id' => 'nullable|exists:parties,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if (!$request->seller_customer_id && !$request->party_id) {
            return back()->withErrors(['seller_customer_id' => 'Please select either a customer or a party.']);
        }

        $customer_data = null;
        if ($request->seller_customer_id) {
            $seller_customer = SellerCustomers::findOrFail($request->seller_customer_id);
            $customer_data = $seller_customer->customer_detail;
        } else {
            $party = Party::findOrFail($request->party_id);
            $customer_data = [
                'name' => $party->name,
                'address' => $party->address,
            ];
        }
        
        $start_date = $request->start_date ? Carbon::parse($request->start_date) : null;
        $end_date = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        // 1. Calculate Opening Balance (ONLY manual Ledgers now)
        $opening_balance = 0;
        if ($start_date) {
            $query_before = Ledger::where('date', '<', $start_date)
                ->when($request->seller_customer_id, function($q) use ($request) {
                    return $q->where('seller_customer_id', $request->seller_customer_id);
                })
                ->when($request->party_id, function($q) use ($request) {
                    return $q->where('party_id', $request->party_id);
                });

            $ledger_debit_before = (clone $query_before)->where('type', 'debit')->sum('amount');
            $ledger_credit_before = (clone $query_before)->where('type', 'credit')->sum('amount');

            $opening_balance = $ledger_debit_before - $ledger_credit_before;
        }

        // 2. Fetch Transactions (ONLY manual Ledgers now)
        $query_ledger = Ledger::where('date', '<=', $end_date)
            ->when($request->seller_customer_id, function($q) use ($request) {
                return $q->where('seller_customer_id', $request->seller_customer_id);
            })
            ->when($request->party_id, function($q) use ($request) {
                return $q->where('party_id', $request->party_id);
            });
        if ($start_date) $query_ledger->where('date', '>=', $start_date);
        
        $transactions = $query_ledger->orderBy('date')->get()->map(function($item) {
            return [
                'date' => $item->date,
                'particulars' => $item->particular_type ?: ($item->description ?: ($item->type === 'debit' ? 'Debit Adjustment' : 'Payment Received')),
                'vch_type' => ucfirst($item->payment_type),
                'vch_no' => $item->voucher_no ?: $item->id,
                'debit' => $item->type === 'debit' ? (float)$item->amount : 0,
                'credit' => $item->type === 'credit' ? (float)$item->amount : 0,
            ];
        });

        $running_balance = $opening_balance;
        $total_debit = 0;
        $total_credit = 0;

        foreach ($transactions as &$tx) {
            $running_balance += ($tx['debit'] - $tx['credit']);
            $tx['balance'] = $running_balance;
            $total_debit += $tx['debit'];
            $total_credit += $tx['credit'];
        }

        return Inertia::render('Ledger/Report', [
            'customers' => SellerCustomers::where('seller_id', Auth::id())->get(),
            'parties' => Party::where('user_id', Auth::id())->get(),
            'report_data' => [
                'company' => Company::where('seller_id', Auth::id())->first(),
                'customer' => $customer_data,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'opening_balance' => $opening_balance,
                'transactions' => $transactions,
                'total_debit' => $total_debit,
                'total_credit' => $total_credit,
                'closing_balance' => $running_balance,
            ],
            'filters' => $request->only(['seller_customer_id', 'party_id', 'start_date', 'end_date'])
        ]);
    }
}
