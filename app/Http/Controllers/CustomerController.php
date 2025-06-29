<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = [
            ['id' => 1, 'name' => 'Alice Brown', 'email' => 'alice@example.com', 'phone' => '081234567890', 'total_orders' => 15, 'last_order' => '2024-06-28', 'status' => 'Active'],
            ['id' => 2, 'name' => 'Bob Taylor', 'email' => 'bob@example.com', 'phone' => '081234567891', 'total_orders' => 8, 'last_order' => '2024-06-25', 'status' => 'Active'],
            ['id' => 3, 'name' => 'Carol Davis', 'email' => 'carol@example.com', 'phone' => '081234567892', 'total_orders' => 22, 'last_order' => '2024-06-29', 'status' => 'VIP'],
            ['id' => 4, 'name' => 'David Lee', 'email' => 'david@example.com', 'phone' => '081234567893', 'total_orders' => 5, 'last_order' => '2024-06-20', 'status' => 'Active'],
            ['id' => 5, 'name' => 'Emma Wilson', 'email' => 'emma@example.com', 'phone' => '081234567894', 'total_orders' => 18, 'last_order' => '2024-06-27', 'status' => 'VIP'],
        ];

        return view('customers.index', compact('customers'));
    }
}
