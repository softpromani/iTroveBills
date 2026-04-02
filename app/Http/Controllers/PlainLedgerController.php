<?php

namespace App\Http\Controllers;

use App\Models\PlainLedger;
use App\Models\SellerCustomers;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Inertia\Inertia;

class PlainLedgerController extends Controller
{
    public function index(Request $request)
    {
        $needs_auth = $request->attributes->get('needs_plain_bill_auth', false);

        $ledgers = PlainLedger::with(['sellerCustomer', 'party'])
            ->where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->paginate(10);
            
        return Inertia::render('PlainLedger/Index', [
            'ledgers' => $ledgers,
            'needs_auth' => $needs_auth
        ]);
    }

    public function auth(Request $request)
    {
        $request->validate(['password' => 'required']);

        if ($request->password === 'Plain@123') {
            Session::put('plain_ledger_authenticated', true);
            Session::put('plain_ledger_expires_at', Carbon::now()->addHour()->toDateTimeString());
            return back()->with('success', 'Authenticated successfully.');
        }

        return back()->with('error', 'Invalid password.');
    }

    public function create()
    {
        $customers = SellerCustomers::where('seller_id', Auth::id())
            ->get();
            
        $parties = Party::where('user_id', Auth::id())->get();
        
        return Inertia::render('PlainLedger/Create', [
            'customers' => $customers,
            'parties' => $parties
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'seller_customer_id' => 'nullable|exists:seller_customers,id',
            'party_id' => 'nullable|exists:parties,id',
            'type' => 'required|in:debit,credit',
            'payment_type' => 'required|in:cash,transfer,sales,purchase',
            'particular_type' => 'required|in:Voucher,Bill,Paid,Receipt',
            'voucher_no' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        if (!$request->seller_customer_id && !$request->party_id) {
            return back()->withErrors(['seller_customer_id' => 'Please select either a customer or a party.']);
        }

        PlainLedger::create([
            'user_id' => Auth::id(),
            'seller_customer_id' => $request->seller_customer_id,
            'party_id' => $request->party_id,
            'type' => $request->type,
            'payment_type' => $request->payment_type,
            'particular_type' => $request->particular_type,
            'voucher_no' => $request->voucher_no,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('plain-ledger.index')->with('success', 'Plain Ledger entry created successfully.');
    }

    public function edit($id)
    {
        $ledger = PlainLedger::findOrFail($id);
        
        if ($ledger->user_id !== Auth::id()) {
            abort(403);
        }

        $customers = SellerCustomers::where('seller_id', Auth::id())
            ->get();
            
        $parties = Party::where('user_id', Auth::id())->get();

        return Inertia::render('PlainLedger/Edit', [
            'ledger' => $ledger,
            'customers' => $customers,
            'parties' => $parties
        ]);
    }

    public function update(Request $request, $id)
    {
        $ledger = PlainLedger::findOrFail($id);

        if ($ledger->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'seller_customer_id' => 'nullable|exists:seller_customers,id',
            'party_id' => 'nullable|exists:parties,id',
            'type' => 'required|in:debit,credit',
            'payment_type' => 'required|in:cash,transfer,sales,purchase',
            'particular_type' => 'required|in:Voucher,Bill,Paid,Receipt',
            'voucher_no' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        if (!$request->seller_customer_id && !$request->party_id) {
            return back()->withErrors(['seller_customer_id' => 'Please select either a customer or a party.']);
        }

        $ledger->update([
            'seller_customer_id' => $request->seller_customer_id,
            'party_id' => $request->party_id,
            'type' => $request->type,
            'payment_type' => $request->payment_type,
            'particular_type' => $request->particular_type,
            'voucher_no' => $request->voucher_no,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('plain-ledger.index')->with('success', 'Plain Ledger entry updated successfully.');
    }

    public function destroy($id)
    {
        $ledger = PlainLedger::findOrFail($id);

        if ($ledger->user_id !== Auth::id()) {
            abort(403);
        }

        $ledger->delete();

        return redirect()->route('plain-ledger.index')->with('success', 'Plain Ledger entry deleted successfully.');
    }

    public function report(Request $request)
    {
        $needs_auth = $request->attributes->get('needs_plain_bill_auth', false);
        
        $customers = SellerCustomers::where('seller_id', Auth::id())->get();
        $parties = Party::where('user_id', Auth::id())->get();

        return Inertia::render('PlainLedger/Report', [
            'customers' => $customers,
            'parties' => $parties,
            'needs_auth' => $needs_auth,
            'filters' => [
                'seller_customer_id' => '',
                'party_id' => '',
                'start_date' => '',
                'end_date' => '',
            ],
            'report_data' => null
        ]);
    }

    public function generate_report(Request $request)
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

        // 1. Get Base Opening Balance (Assuming Plain Ledger starts from 0 as it's separate)
        // If user wants opening balance from the main module, they can define it.
        // For now, I'll stick to a simple separate ledger logic.
        $opening_balance = 0;
        
        if ($start_date) {
            $query_before = PlainLedger::where('user_id', Auth::id())
                ->where('date', '<', $start_date)
                ->when($request->seller_customer_id, function($q) use ($request) {
                    return $q->where('seller_customer_id', $request->seller_customer_id);
                })
                ->when($request->party_id, function($q) use ($request) {
                    return $q->where('party_id', $request->party_id);
                });

            $ledger_debit_before = (clone $query_before)->where('type', 'debit')->sum('amount');
            $ledger_credit_before = (clone $query_before)->where('type', 'credit')->sum('amount');

            $opening_balance += ($ledger_debit_before - $ledger_credit_before);
        }

        // 2. Fetch Transactions
        $query_ledger = PlainLedger::where('user_id', Auth::id())
            ->where('date', '<=', $end_date)
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
                'particulars' => $item->particular_type ?: 'Plain Transaction',
                'vch_type' => ucfirst($item->payment_type),
                'vch_no' => $item->voucher_no ?: $item->id,
                'debit' => $item->type === 'debit' ? (float)$item->amount : 0,
                'credit' => $item->type === 'credit' ? (float)$item->amount : 0,
            ];
        });

        $total_debit = $transactions->sum('debit');
        $total_credit = $transactions->sum('credit');
        $closing_balance = $opening_balance + ($total_debit - $total_credit);

        return Inertia::render('PlainLedger/Report', [
            'customers' => SellerCustomers::where('seller_id', Auth::id())->get(),
            'parties' => Party::where('user_id', Auth::id())->get(),
            'report_data' => [
                'company' => \App\Models\Company::where('seller_id', Auth::id())->first(),
                'customer' => $customer_data,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'opening_balance' => (float)$opening_balance,
                'transactions' => $transactions,
                'total_debit' => (float)$total_debit,
                'total_credit' => (float)$total_credit,
                'closing_balance' => (float)$closing_balance,
            ],
            'filters' => $request->only(['seller_customer_id', 'party_id', 'start_date', 'end_date'])
        ]);
    }
}
