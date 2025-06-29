<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckBaristaAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('authenticated')) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Session::get('user');
        
        // Jika bukan barista, redirect sesuai role
        if ($user['role'] !== 'Barista') {
            if ($user['role'] === 'Kasir') {
                return redirect()->route('cashier.index')->with('error', 'Akses ditolak. Halaman ini hanya untuk barista.');
            }
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Halaman ini hanya untuk barista.');
        }

        return $next($request);
    }
}
