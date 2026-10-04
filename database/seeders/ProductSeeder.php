<?php

// ====================================================================
// database/seeders/ProductSeeder.php
// REQUIREMENT 2: seed 50+ produk realistis, dikelompokkan sesuai
// kategori yang benar (bukan asal random), pakai ProductFactory untuk
// atribut harga/stok/deskripsi acak yang tetap realistis.
// ====================================================================

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $byCategory = [
            'Elektronik' => [
                'Mouse Wireless Logitech M170', 'Keyboard Mechanical RGB K1', 'Headset Gaming HyperX Cloud',
                'Power Bank 10000mAh Anker', 'Charger USB-C 20W', 'Kabel Data Type-C 1m',
                'Speaker Bluetooth JBL Go 3', 'Earphone TWS Xiaomi Buds 3', 'Flashdisk Sandisk 64GB',
                'Mousepad Gaming Large', 'Webcam Logitech C270', 'Smartwatch Fitness Band 7',
            ],
            'Fashion Pria' => [
                'Kemeja Flanel Pria Lengan Panjang', 'Kaos Polos Cotton Combed 30s', 'Celana Chino Slim Fit',
                'Jaket Bomber Pria', 'Sweater Rajut Pria', 'Sandal Jepit Pria',
            ],
            'Fashion Wanita' => [
                'Blouse Wanita Katun', 'Dress Casual Wanita', 'Rok Plisket Wanita',
                'Cardigan Rajut Wanita', 'Hijab Segi Empat Voal', 'Tas Selempang Wanita',
            ],
            'Makanan & Minuman' => [
                'Kopi Arabika Gayo 200g', 'Teh Hijau Celup Isi 25', 'Madu Hutan Asli 500ml',
                'Keripik Singkong Balado', 'Cokelat Batang Dark 70%', 'Sereal Gandum Utuh 500g',
            ],
            'Alat Tulis' => [
                'Buku Tulis 38 Lembar', 'Pulpen Gel 0.5mm Set 12', 'Pensil 2B Set 12',
                'Penghapus Karet Putih', 'Spidol Whiteboard Set 4 Warna', 'Stabilo Highlighter Set 6',
            ],
            'Perabotan Rumah' => [
                'Rak Buku Minimalis 3 Susun', 'Lampu Meja LED', 'Keset Kaki Anti Slip',
                'Gantungan Baju Dinding', 'Tempat Sampah Lipat', 'Organizer Laci Plastik',
            ],
            'Olahraga' => [
                'Matras Yoga 10mm', 'Dumbbell Set 5kg', 'Botol Minum Olahraga 1L',
                'Resistance Band Set', 'Sepatu Lari Pria', 'Raket Badminton Carbon',
            ],
            'Kesehatan & Kecantikan' => [
                'Hand Sanitizer 500ml', 'Masker Medis Isi 50', 'Vitamin C 1000mg Isi 30',
                'Sunscreen SPF 50', 'Sabun Cuci Muka Charcoal', 'Lotion Pelembab Kulit',
            ],
            'Mainan & Hobi' => [
                'Puzzle 500 Keping', 'Lego Classic Creative Box', 'Mainan Mobil Remote Control',
                'Rubik Cube 3x3', 'Boneka Beruang 30cm', 'Kartu UNO Original',
            ],
            'Otomotif' => [
                'Pewangi Mobil Gantung', 'Lap Microfiber Mobil Set 3', 'Dudukan HP Mobil Magnet',
                'Minyak Wangi Mobil Spray', 'Sarung Jok Motor', 'Lampu LED Motor H4',
            ],
        ];

        $total = 0;

        foreach ($byCategory as $categoryName => $productNames) {
            $category = Category::where('slug', Str::slug($categoryName))->first();

            if (! $category) {
                continue; // jaga-jaga kalau CategorySeeder belum dijalankan
            }

            foreach ($productNames as $name) {
                Product::factory()->state([
                    'category_id' => $category->id,
                    'name'        => $name,
                    'slug'        => Str::slug($name) . '-' . Str::random(5),
                ])->create();
                $total++;
            }
        }

        $this->command?->info("ProductSeeder: {$total} produk dibuat.");
    }
}
