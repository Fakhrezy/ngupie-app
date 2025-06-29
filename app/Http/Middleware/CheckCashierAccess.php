<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckCashierAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('authenticated')) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Session::get('user');

        // Jika bukan kasir, redirect ke dashboard normal
        if ($user['role'] !== 'Kasir') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Halaman ini hanya untuk kasir.');
        }

        return $next($request);
    }
}
