<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckAdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('authenticated')) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }        $user = Session::get('user');
        
        // Jika kasir, redirect ke halaman kasir
        if ($user['role'] === 'Kasir') {
            return redirect()->route('cashier.index')->with('info', 'Anda telah diarahkan ke halaman kasir.');
        }
        
        // Jika barista, redirect ke halaman barista
        if ($user['role'] === 'Barista') {
            return redirect()->route('barista.index')->with('info', 'Anda telah diarahkan ke halaman barista.');
        }

        return $next($request);
    }
}
