<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = [
            ['id' => 1, 'name' => 'Espresso', 'category' => 'Coffee', 'price' => 15000, 'description' => 'Single shot espresso yang kuat dan aromatic', 'status' => 'Available'],
            ['id' => 2, 'name' => 'Cappuccino', 'category' => 'Coffee', 'price' => 25000, 'description' => 'Espresso dengan steamed milk dan foam', 'status' => 'Available'],
            ['id' => 3, 'name' => 'Latte', 'category' => 'Coffee', 'price' => 28000, 'description' => 'Espresso dengan steamed milk dan light foam', 'status' => 'Available'],
            ['id' => 4, 'name' => 'Americano', 'category' => 'Coffee', 'price' => 18000, 'description' => 'Espresso dengan hot water', 'status' => 'Available'],
            ['id' => 5, 'name' => 'Mocha', 'category' => 'Coffee', 'price' => 32000, 'description' => 'Espresso dengan chocolate dan steamed milk', 'status' => 'Available'],
            ['id' => 6, 'name' => 'Croissant', 'category' => 'Pastry', 'price' => 12000, 'description' => 'Fresh baked butter croissant', 'status' => 'Available'],
            ['id' => 7, 'name' => 'Chocolate Cake', 'category' => 'Dessert', 'price' => 22000, 'description' => 'Rich chocolate cake slice', 'status' => 'Out of Stock'],
        ];

        return view('menus.index', compact('menus'));
    }
}
