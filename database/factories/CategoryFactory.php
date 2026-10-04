<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Elektronik', 'Fashion Pria', 'Fashion Wanita', 'Makanan & Minuman',
            'Alat Tulis', 'Perabotan Rumah', 'Olahraga', 'Kesehatan & Kecantikan',
            'Mainan & Hobi', 'Otomotif',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
