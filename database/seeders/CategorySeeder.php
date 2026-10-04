<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Elektronik', 'Fashion Pria', 'Fashion Wanita', 'Makanan & Minuman',
            'Alat Tulis', 'Perabotan Rumah', 'Olahraga', 'Kesehatan & Kecantikan',
            'Mainan & Hobi', 'Otomotif',
        ];

        foreach ($names as $name) {
            Category::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }
    }
}
