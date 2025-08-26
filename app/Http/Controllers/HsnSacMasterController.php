<?php

namespace App\Http\Controllers;

use App\Models\HsnSacMaster;
use Illuminate\Http\Request;

class HsnSacMasterController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $results = HsnSacMaster::where('code', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(10)
            ->get(['code', 'description']);

        return response()->json($results);
    }
}
