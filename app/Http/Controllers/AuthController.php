<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect berdasarkan role
        if (Session::get('authenticated')) {
            $user = Session::get('user');
            if ($user['role'] === 'Kasir') {
                return redirect()->route('cashier.index');
            } elseif ($user['role'] === 'Barista') {
                return redirect()->route('barista.index');
            } elseif ($user['role'] === 'Staff') {
                return redirect()->route('staff.index');
            } elseif ($user['role'] === 'Manager') {
                return redirect()->route('manager.index');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Data dummy untuk login
        $users = [
            ['email' => 'admin@coffeshop.com', 'password' => 'admin123', 'name' => 'Admin Coffee Shop', 'role' => 'Administrator'],
            ['email' => 'manager@coffeshop.com', 'password' => 'manager123', 'name' => 'Manager Coffee Shop', 'role' => 'Manager'],
            ['email' => 'staff@coffeshop.com', 'password' => 'staff123', 'name' => 'Staff Coffee Shop', 'role' => 'Staff'],
            ['email' => 'barista@coffeshop.com', 'password' => 'barista123', 'name' => 'Barista Coffee Shop', 'role' => 'Barista'],
            ['email' => 'kasir@coffeshop.com', 'password' => 'kasir123', 'name' => 'Kasir Coffee Shop', 'role' => 'Kasir'],
        ];

        $email = $request->email;
        $password = $request->password;

        // Cek kredensial
        $user = collect($users)->firstWhere(function ($user) use ($email, $password) {
            return $user['email'] === $email && $user['password'] === $password;
        });

        if ($user) {
            // Simpan data user ke session
            Session::put('user', $user);
            Session::put('authenticated', true);            // Redirect berdasarkan role
            if ($user['role'] === 'Kasir') {
                return redirect()->route('cashier.index')->with('success', 'Login berhasil! Selamat datang ' . $user['name']);
            } elseif ($user['role'] === 'Barista') {
                return redirect()->route('barista.index')->with('success', 'Login berhasil! Selamat datang ' . $user['name']);
            } elseif ($user['role'] === 'Staff') {
                return redirect()->route('staff.index')->with('success', 'Login berhasil! Selamat datang ' . $user['name']);
            } elseif ($user['role'] === 'Manager') {
                return redirect()->route('manager.index')->with('success', 'Login berhasil! Selamat datang ' . $user['name']);
            }

            return redirect()->route('dashboard')->with('success', 'Login berhasil! Selamat datang ' . $user['name']);
        }

        return back()->withErrors(['login' => 'Email atau password salah!'])->withInput();
    }

    public function logout()
    {
        Session::forget('user');
        Session::forget('authenticated');

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
