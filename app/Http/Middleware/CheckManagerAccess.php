<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckManagerAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('authenticated')) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Session::get('user');

        // Jika bukan manager atau admin, redirect sesuai role
        if (!in_array($user['role'], ['Manager', 'Administrator'])) {
            if ($user['role'] === 'Kasir') {
                return redirect()->route('cashier.index')->with('error', 'Akses ditolak. Halaman ini hanya untuk manager.');
            } elseif ($user['role'] === 'Barista') {
                return redirect()->route('barista.index')->with('error', 'Akses ditolak. Halaman ini hanya untuk manager.');
            } elseif ($user['role'] === 'Staff') {
                return redirect()->route('staff.index')->with('error', 'Akses ditolak. Halaman ini hanya untuk manager.');
            }
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Halaman ini hanya untuk manager.');
        }

        return $next($request);
    }
}
