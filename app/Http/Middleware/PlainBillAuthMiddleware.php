<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class PlainBillAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isLedger = $request->is('ink*');
        
        $authenticated = $isLedger ? Session::get('plain_ledger_authenticated') : Session::get('plain_bill_authenticated');
        $expiresAt = $isLedger ? Session::get('plain_ledger_expires_at') : Session::get('plain_bill_expires_at');

        if ($authenticated && $expiresAt && Carbon::now()->lessThan(Carbon::parse($expiresAt))) {
            return $next($request);
        }

        // Not authenticated or expired
        if ($request->routeIs('book.auth') || $request->routeIs('ink.auth')) {
            return $next($request);
        }

        // Only allow GET for landing/index with a flag
        if ($request->isMethod('GET') && (
            $request->routeIs('book.index') || 
            $request->routeIs('book.list') ||
            $request->routeIs('ink.index') ||
            $request->routeIs('ink.report')
        )) {
            $request->attributes->set('needs_plain_bill_auth', true);
            return $next($request);
        }

        // Block everything else
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized. Please authenticate first.'], 401);
        }

        // Redirect to appropriate landing page
        if ($isLedger) {
            return redirect()->route('ink.index')->with('error', 'Please authenticate to access this module.');
        }

        return redirect()->route('book.index')->with('error', 'Please authenticate to access this module.');
    }
}
