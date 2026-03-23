<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PartyController extends Controller
{
    public function index()
    {
        $parties = Party::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Parties/Index', [
            'parties' => $parties
        ]);
    }

    public function create()
    {
        return Inertia::render('Parties/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
        ]);

        Party::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'opening_balance' => $request->opening_balance ?? 0,
        ]);

        return redirect()->route('parties.index')->with('success', 'Party created successfully.');
    }

    public function edit(Party $party)
    {
        if ($party->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Parties/Edit', [
            'party' => $party
        ]);
    }

    public function update(Request $request, Party $party)
    {
        if ($party->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
        ]);

        $party->update([
            'name' => $request->name,
            'address' => $request->address,
            'opening_balance' => $request->opening_balance ?? 0,
        ]);

        return redirect()->route('parties.index')->with('success', 'Party updated successfully.');
    }

    public function destroy(Party $party)
    {
        if ($party->user_id !== Auth::id()) {
            abort(403);
        }

        $party->delete();

        return redirect()->route('parties.index')->with('success', 'Party deleted successfully.');
    }
}
