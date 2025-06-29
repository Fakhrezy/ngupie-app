<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = [
            ['id' => 1, 'name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'Administrator', 'status' => 'Active', 'created_at' => '2023-01-15'],
            ['id' => 2, 'name' => 'Manager User', 'email' => 'manager@example.com', 'role' => 'Manager', 'status' => 'Active', 'created_at' => '2023-02-20'],
            ['id' => 3, 'name' => 'Staff User', 'email' => 'staff@example.com', 'role' => 'Staff', 'status' => 'Inactive', 'created_at' => '2023-03-10'],
            ['id' => 4, 'name' => 'Cashier User', 'email' => 'cashier@example.com', 'role' => 'Cashier', 'status' => 'Active', 'created_at' => '2023-04-05'],
        ];

        return view('users.index', compact('users'));
    }
}
