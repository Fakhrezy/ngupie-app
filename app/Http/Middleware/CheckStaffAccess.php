<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckStaffAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('authenticated')) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Session::get('user');

        // Jika bukan staff, redirect sesuai role
        if ($user['role'] !== 'Staff') {
            if ($user['role'] === 'Kasir') {
                return redirect()->route('cashier.index')->with('error', 'Akses ditolak. Halaman ini hanya untuk staff.');
            } elseif ($user['role'] === 'Barista') {
                return redirect()->route('barista.index')->with('error', 'Akses ditolak. Halaman ini hanya untuk staff.');
            }
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Halaman ini hanya untuk staff.');
        }

        return $next($request);
    }
}
