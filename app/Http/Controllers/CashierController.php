<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CashierController extends Controller
{
    public function index()
    {
        // Data dummy menu untuk kasir
        $menus = [
            [
                'id' => 1,
                'name' => 'Espresso',
                'category' => 'Hot Coffee',
                'price' => 25000,
                'image' => 'https://via.placeholder.com/100x100/8B4513/FFFFFF?text=ESP',
                'stock' => 50
            ],
            [
                'id' => 2,
                'name' => 'Cappuccino',
                'category' => 'Hot Coffee',
                'price' => 35000,
                'image' => 'https://via.placeholder.com/100x100/8B4513/FFFFFF?text=CAP',
                'stock' => 30
            ],
            [
                'id' => 3,
                'name' => 'Latte',
                'category' => 'Hot Coffee',
                'price' => 40000,
                'image' => 'https://via.placeholder.com/100x100/8B4513/FFFFFF?text=LAT',
                'stock' => 25
            ],
            [
                'id' => 4,
                'name' => 'Iced Coffee',
                'category' => 'Cold Coffee',
                'price' => 30000,
                'image' => 'https://via.placeholder.com/100x100/4682B4/FFFFFF?text=ICE',
                'stock' => 40
            ],
            [
                'id' => 5,
                'name' => 'Frappuccino',
                'category' => 'Cold Coffee',
                'price' => 45000,
                'image' => 'https://via.placeholder.com/100x100/4682B4/FFFFFF?text=FRP',
                'stock' => 20
            ],
            [
                'id' => 6,
                'name' => 'Americano',
                'category' => 'Hot Coffee',
                'price' => 28000,
                'image' => 'https://via.placeholder.com/100x100/8B4513/FFFFFF?text=AME',
                'stock' => 35
            ],
            [
                'id' => 7,
                'name' => 'Croissant',
                'category' => 'Pastry',
                'price' => 20000,
                'image' => 'https://via.placeholder.com/100x100/DAA520/FFFFFF?text=CRO',
                'stock' => 15
            ],
            [
                'id' => 8,
                'name' => 'Muffin',
                'category' => 'Pastry',
                'price' => 25000,
                'image' => 'https://via.placeholder.com/100x100/DAA520/FFFFFF?text=MUF',
                'stock' => 12
            ]
        ];

        $categories = ['All', 'Hot Coffee', 'Cold Coffee', 'Pastry'];

        return view('cashier.index', compact('menus', 'categories'));
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        // Simulasi response untuk AJAX
        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan ke keranjang',
            'cart_count' => rand(1, 10) // Dummy cart count
        ]);
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'payment_method' => 'required|in:cash,card,digital',
            'total' => 'required|numeric|min:0'
        ]);

        // Simulasi proses pembayaran
        $orderId = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diproses',
            'order_id' => $orderId,
            'total' => $request->total
        ]);
    }
}
