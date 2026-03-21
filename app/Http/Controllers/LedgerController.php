<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\SellerCustomers;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LedgerController extends Controller
{
    public function index()
    {
        $ledgers = Ledger::with(['sellerCustomer', 'party'])
            ->where('user_id', Auth::id())
            ->latest('date')
            ->paginate(10);

        return Inertia::render('Ledger/Index', [
            'ledgers' => $ledgers
        ]);
    }

    public function create()
    {
        $customers = SellerCustomers::where('seller_id', Auth::id())->get();
        $parties = Party::where('user_id', Auth::id())->get();
        
        return Inertia::render('Ledger/Create', [
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
            'payment_type' => 'required|in:cash,transfer,sales',
            'particular_type' => 'nullable|in:Voucher,Bill',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Ensure either customer or party is selected
        if (!$request->seller_customer_id && !$request->party_id) {
            return back()->withErrors(['seller_customer_id' => 'Please select either a customer or a party.']);
        }

        Ledger::create([
            'user_id' => Auth::id(),
            'seller_customer_id' => $request->seller_customer_id,
            'party_id' => $request->party_id,
            'type' => $request->type,
            'payment_type' => $request->payment_type,
            'particular_type' => $request->particular_type,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->route('ledgers.index')->with('success', 'Ledger entry created successfully.');
    }

    public function edit(Ledger $ledger)
    {
        if ($ledger->user_id !== Auth::id()) {
            abort(403);
        }

        $customers = SellerCustomers::where('seller_id', Auth::id())->get();
        $parties = Party::where('user_id', Auth::id())->get();

        return Inertia::render('Ledger/Edit', [
            'ledger' => $ledger,
            'customers' => $customers,
            'parties' => $parties
        ]);
    }

    public function update(Request $request, Ledger $ledger)
    {
        if ($ledger->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'seller_customer_id' => 'nullable|exists:seller_customers,id',
            'party_id' => 'nullable|exists:parties,id',
            'type' => 'required|in:debit,credit',
            'payment_type' => 'required|in:cash,transfer,sales',
            'particular_type' => 'nullable|in:Voucher,Bill',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
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
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->route('ledgers.index')->with('success', 'Ledger entry updated successfully.');
    }

    public function destroy(Ledger $ledger)
    {
        if ($ledger->user_id !== Auth::id()) {
            abort(403);
        }

        $ledger->delete();

        return redirect()->route('ledgers.index')->with('success', 'Ledger entry deleted successfully.');
    }
}
