<?php

namespace App\Http\Controllers;

use App\Models\MasterGstConfig;
use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class MasterGstConfigController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $companies = $user->companies;
        
        // Active company logic - if many, first for now or let them choose
        // Usually, the app might have a way to select active company. 
        // For now, I'll fetch configs for all companies the user owns to show in a list or just the first.
        $configs = MasterGstConfig::whereIn('company_id', $companies->pluck('id'))->get()->keyBy('company_id');

        return Inertia::render('MasterGST/Settings', [
            'companies' => $companies,
            'configs' => $configs
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'client_id' => 'required',
            'client_secret' => 'required',
            'username' => 'required',
            'password' => 'required',
            'gstin' => 'required',
            'email' => 'required|email',
            'gst_base_url' => 'required|url',
            'ip_address' => 'required',
        ]);

        $config = MasterGstConfig::updateOrCreate(
            ['company_id' => $request->company_id],
            $request->only(['client_id', 'client_secret', 'username', 'password', 'gstin', 'email', 'gst_base_url', 'ip_address'])
        );

        return back()->with('success', 'MasterGST Settings updated successfully');
    }
}
