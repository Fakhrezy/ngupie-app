<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = [
            ['id' => 1, 'customer' => 'Alice Brown', 'items' => 'Cappuccino, Croissant', 'total' => 37000, 'status' => 'Completed', 'date' => '2024-06-29 14:30', 'payment_method' => 'Cash'],
            ['id' => 2, 'customer' => 'Bob Taylor', 'items' => 'Latte, Americano', 'total' => 46000, 'status' => 'In Progress', 'date' => '2024-06-29 15:15', 'payment_method' => 'Card'],
            ['id' => 3, 'customer' => 'Carol Davis', 'items' => 'Espresso x2', 'total' => 30000, 'status' => 'Pending', 'date' => '2024-06-29 15:45', 'payment_method' => 'Cash'],
            ['id' => 4, 'customer' => 'David Lee', 'items' => 'Cappuccino, Latte, Espresso', 'total' => 68000, 'status' => 'Completed', 'date' => '2024-06-29 13:20', 'payment_method' => 'Digital Wallet'],
            ['id' => 5, 'customer' => 'Emma Wilson', 'items' => 'Mocha, Chocolate Cake', 'total' => 54000, 'status' => 'In Progress', 'date' => '2024-06-29 16:00', 'payment_method' => 'Card'],
            ['id' => 6, 'customer' => 'Frank Miller', 'items' => 'Americano x2, Croissant', 'total' => 48000, 'status' => 'Completed', 'date' => '2024-06-29 12:45', 'payment_method' => 'Cash'],
        ];

        return view('orders.index', compact('orders'));
    }
}
