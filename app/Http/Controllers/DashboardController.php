<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dummy untuk dashboard
        $users = [
            ['id' => 1, 'name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'Administrator', 'status' => 'Active'],
            ['id' => 2, 'name' => 'Manager User', 'email' => 'manager@example.com', 'role' => 'Manager', 'status' => 'Active'],
            ['id' => 3, 'name' => 'Staff User', 'email' => 'staff@example.com', 'role' => 'Staff', 'status' => 'Inactive'],
        ];

        $employees = [
            ['id' => 1, 'name' => 'John Doe', 'position' => 'Barista', 'department' => 'Coffee Bar', 'salary' => 4500000, 'join_date' => '2023-01-15'],
            ['id' => 2, 'name' => 'Jane Smith', 'position' => 'Cashier', 'department' => 'Front Office', 'salary' => 3500000, 'join_date' => '2023-02-20'],
            ['id' => 3, 'name' => 'Mike Johnson', 'position' => 'Manager', 'department' => 'Management', 'salary' => 6000000, 'join_date' => '2023-03-10'],
            ['id' => 4, 'name' => 'Sarah Wilson', 'position' => 'Assistant Barista', 'department' => 'Coffee Bar', 'salary' => 3800000, 'join_date' => '2023-04-05'],
        ];

        $customers = [
            ['id' => 1, 'name' => 'Alice Brown', 'email' => 'alice@example.com', 'phone' => '081234567890', 'total_orders' => 15, 'last_order' => '2024-06-28'],
            ['id' => 2, 'name' => 'Bob Taylor', 'email' => 'bob@example.com', 'phone' => '081234567891', 'total_orders' => 8, 'last_order' => '2024-06-25'],
            ['id' => 3, 'name' => 'Carol Davis', 'email' => 'carol@example.com', 'phone' => '081234567892', 'total_orders' => 22, 'last_order' => '2024-06-29'],
            ['id' => 4, 'name' => 'David Lee', 'email' => 'david@example.com', 'phone' => '081234567893', 'total_orders' => 5, 'last_order' => '2024-06-20'],
        ];

        $menus = [
            ['id' => 1, 'name' => 'Espresso', 'category' => 'Coffee', 'price' => 15000, 'description' => 'Single shot espresso yang kuat dan aromatic', 'status' => 'Available'],
            ['id' => 2, 'name' => 'Cappuccino', 'category' => 'Coffee', 'price' => 25000, 'description' => 'Espresso dengan steamed milk dan foam', 'status' => 'Available'],
            ['id' => 3, 'name' => 'Latte', 'category' => 'Coffee', 'price' => 28000, 'description' => 'Espresso dengan steamed milk dan light foam', 'status' => 'Available'],
            ['id' => 4, 'name' => 'Americano', 'category' => 'Coffee', 'price' => 18000, 'description' => 'Espresso dengan hot water', 'status' => 'Available'],
            ['id' => 5, 'name' => 'Croissant', 'category' => 'Pastry', 'price' => 12000, 'description' => 'Fresh baked butter croissant', 'status' => 'Out of Stock'],
        ];

        $orders = [
            ['id' => 1, 'customer' => 'Alice Brown', 'items' => 'Cappuccino, Croissant', 'total' => 37000, 'status' => 'Completed', 'date' => '2024-06-29 14:30'],
            ['id' => 2, 'customer' => 'Bob Taylor', 'items' => 'Latte, Americano', 'total' => 46000, 'status' => 'In Progress', 'date' => '2024-06-29 15:15'],
            ['id' => 3, 'customer' => 'Carol Davis', 'items' => 'Espresso x2', 'total' => 30000, 'status' => 'Pending', 'date' => '2024-06-29 15:45'],
            ['id' => 4, 'customer' => 'David Lee', 'items' => 'Cappuccino, Latte, Espresso', 'total' => 68000, 'status' => 'Completed', 'date' => '2024-06-29 13:20'],
        ];

        // Statistics untuk dashboard
        $stats = [
            'total_users' => count($users),
            'total_employees' => count($employees),
            'total_customers' => count($customers),
            'total_menus' => count($menus),
            'total_orders' => count($orders),
            'today_revenue' => array_sum(array_column(array_filter($orders, function($order) {
                return $order['status'] === 'Completed';
            }), 'total')),
        ];

        return view('dashboard', compact('users', 'employees', 'customers', 'menus', 'orders', 'stats'));
    }
}
