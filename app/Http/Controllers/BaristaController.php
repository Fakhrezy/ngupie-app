<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BaristaController extends Controller
{
    public function index()
    {
        // Data dummy pesanan untuk barista
        $orders = [
            [
                'id' => 'ORD-001',
                'customer_name' => 'John Doe',
                'items' => [
                    ['name' => 'Cappuccino', 'quantity' => 2, 'notes' => 'Extra foam'],
                    ['name' => 'Latte', 'quantity' => 1, 'notes' => 'Oat milk']
                ],
                'status' => 'pending',
                'order_time' => '2025-06-29 09:15:00',
                'estimated_time' => 8,
                'priority' => 'normal'
            ],
            [
                'id' => 'ORD-002',
                'customer_name' => 'Jane Smith',
                'items' => [
                    ['name' => 'Espresso', 'quantity' => 1, 'notes' => 'Double shot'],
                    ['name' => 'Americano', 'quantity' => 1, 'notes' => '']
                ],
                'status' => 'in_progress',
                'order_time' => '2025-06-29 09:12:00',
                'estimated_time' => 5,
                'priority' => 'high'
            ],
            [
                'id' => 'ORD-003',
                'customer_name' => 'Bob Wilson',
                'items' => [
                    ['name' => 'Frappuccino', 'quantity' => 1, 'notes' => 'Less sugar'],
                    ['name' => 'Croissant', 'quantity' => 2, 'notes' => 'Warm up']
                ],
                'status' => 'pending',
                'order_time' => '2025-06-29 09:18:00',
                'estimated_time' => 12,
                'priority' => 'normal'
            ],
            [
                'id' => 'ORD-004',
                'customer_name' => 'Alice Brown',
                'items' => [
                    ['name' => 'Iced Coffee', 'quantity' => 3, 'notes' => 'Extra ice']
                ],
                'status' => 'completed',
                'order_time' => '2025-06-29 09:08:00',
                'estimated_time' => 4,
                'priority' => 'normal'
            ],
            [
                'id' => 'ORD-005',
                'customer_name' => 'Charlie Davis',
                'items' => [
                    ['name' => 'Latte', 'quantity' => 2, 'notes' => 'Decaf, soy milk'],
                    ['name' => 'Muffin', 'quantity' => 1, 'notes' => '']
                ],
                'status' => 'pending',
                'order_time' => '2025-06-29 09:20:00',
                'estimated_time' => 7,
                'priority' => 'normal'
            ]
        ];

        // Urutkan berdasarkan prioritas dan waktu
        usort($orders, function($a, $b) {
            if ($a['priority'] === 'high' && $b['priority'] !== 'high') return -1;
            if ($b['priority'] === 'high' && $a['priority'] !== 'high') return 1;
            return strtotime($a['order_time']) - strtotime($b['order_time']);
        });

        return view('barista.index', compact('orders'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        // Simulasi update status
        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui',
            'order_id' => $request->order_id,
            'new_status' => $request->status
        ]);
    }

    public function getRecipe($itemName)
    {
        // Data dummy resep
        $recipes = [
            'Espresso' => [
                'name' => 'Espresso',
                'description' => 'Kopi hitam pekat yang menjadi dasar berbagai minuman kopi',
                'ingredients' => [
                    '18-20g biji kopi espresso yang digiling halus',
                    'Air panas 90-96°C'
                ],
                'steps' => [
                    'Panaskan mesin espresso hingga suhu optimal',
                    'Masukkan 18-20g kopi bubuk ke dalam portafilter',
                    'Ratakan dan padatkan kopi dengan tamper',
                    'Pasang portafilter ke mesin',
                    'Ekstrak selama 25-30 detik untuk 30ml espresso',
                    'Sajikan segera dalam cangkir espresso yang telah dihangatkan'
                ],
                'tips' => [
                    'Pastikan grind size konsisten',
                    'Suhu air ideal 93-96°C',
                    'Waktu ekstraksi 25-30 detik',
                    'Crema harus berwarna golden brown'
                ],
                'time' => '2-3 menit'
            ],
            'Cappuccino' => [
                'name' => 'Cappuccino',
                'description' => 'Kombinasi espresso dengan steamed milk dan milk foam',
                'ingredients' => [
                    '1 shot espresso (30ml)',
                    '60ml susu segar',
                    'Bubuk kayu manis (opsional)'
                ],
                'steps' => [
                    'Buat 1 shot espresso dalam cangkir cappuccino',
                    'Steam susu hingga suhu 65-70°C dengan microfoam halus',
                    'Tuang steamed milk ke dalam espresso dengan gerakan melingkar',
                    'Tambahkan foam di atas hingga mencapai rasio 1:1:1',
                    'Buat latte art sederhana (opsional)',
                    'Taburi bubuk kayu manis jika diminta'
                ],
                'tips' => [
                    'Rasio espresso:milk:foam = 1:1:1',
                    'Susu jangan terlalu panas (max 70°C)',
                    'Microfoam harus halus dan mengkilap',
                    'Sajikan segera setelah dibuat'
                ],
                'time' => '3-4 menit'
            ],
            'Latte' => [
                'name' => 'Latte',
                'description' => 'Espresso dengan steamed milk dan sedikit foam',
                'ingredients' => [
                    '1-2 shot espresso (30-60ml)',
                    '150ml susu segar',
                    'Sirup vanilla (opsional)'
                ],
                'steps' => [
                    'Buat 1-2 shot espresso dalam gelas latte',
                    'Tambahkan sirup jika diminta',
                    'Steam susu hingga 65-70°C dengan microfoam minimal',
                    'Tuang steamed milk perlahan ke dalam espresso',
                    'Buat latte art dengan teknik pouring',
                    'Sajikan dengan sedotan atau sendok kecil'
                ],
                'tips' => [
                    'Rasio espresso:milk = 1:3',
                    'Microfoam tipis saja (5mm)',
                    'Ideal untuk latte art',
                    'Gunakan susu dengan fat content 3.25%'
                ],
                'time' => '4-5 menit'
            ],
            'Americano' => [
                'name' => 'Americano',
                'description' => 'Espresso yang diencerkan dengan air panas',
                'ingredients' => [
                    '1-2 shot espresso (30-60ml)',
                    '120ml air panas',
                    'Gula/pemanis (opsional)'
                ],
                'steps' => [
                    'Siapkan cangkir atau gelas americano',
                    'Buat 1-2 shot espresso',
                    'Panaskan air hingga 80-85°C',
                    'Tuang air panas ke dalam cangkir terlebih dahulu',
                    'Tambahkan espresso perlahan di atas air',
                    'Aduk ringan dan sajikan'
                ],
                'tips' => [
                    'Air panas dituang dulu untuk menjaga crema',
                    'Rasio espresso:air = 1:2',
                    'Jangan gunakan air mendidih',
                    'Sajikan dengan gula di samping'
                ],
                'time' => '2-3 menit'
            ],
            'Iced Coffee' => [
                'name' => 'Iced Coffee',
                'description' => 'Kopi dingin yang menyegarkan',
                'ingredients' => [
                    '2 shot espresso (60ml)',
                    '100ml air dingin',
                    'Es batu secukupnya',
                    'Sirup simple (opsional)'
                ],
                'steps' => [
                    'Buat 2 shot espresso dan biarkan dingin',
                    'Isi gelas dengan es batu',
                    'Tambahkan sirup simple jika diminta',
                    'Tuang espresso dingin ke dalam gelas',
                    'Tambahkan air dingin',
                    'Aduk dan sajikan dengan sedotan'
                ],
                'tips' => [
                    'Biarkan espresso dingin dulu sebelum dituang',
                    'Gunakan es batu yang bersih',
                    'Bisa ditambah susu dingin',
                    'Sajikan segera sebelum es mencair'
                ],
                'time' => '3-4 menit'
            ],
            'Frappuccino' => [
                'name' => 'Frappuccino',
                'description' => 'Minuman kopi dingin yang diblender dengan es',
                'ingredients' => [
                    '1 shot espresso dingin',
                    '150ml susu dingin',
                    '1 cup es batu',
                    '2 sdm gula/sirup vanilla',
                    'Whipped cream'
                ],
                'steps' => [
                    'Buat espresso dan dinginkan',
                    'Masukkan es, espresso, susu, dan pemanis ke blender',
                    'Blend hingga halus dan berbusa',
                    'Tuang ke dalam gelas tinggi',
                    'Tambahkan whipped cream di atas',
                    'Beri topping sesuai permintaan'
                ],
                'tips' => [
                    'Jangan blend terlalu lama agar es tidak terlalu halus',
                    'Konsistensi harus thick tapi bisa diminum dengan sedotan',
                    'Sajikan segera setelah dibuat',
                    'Gunakan sedotan besar'
                ],
                'time' => '4-5 menit'
            ]
        ];

        $recipe = $recipes[$itemName] ?? null;

        if (!$recipe) {
            return response()->json([
                'success' => false,
                'message' => 'Resep tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'recipe' => $recipe
        ]);
    }
}
