<?php

// ====================================================================
// database/seeders/ReviewSeeder.php
// Seed beberapa review contoh, supaya Product::averageRating() dan
// demo eager loading (lihat README) ada data nyata untuk dicoba.
// ====================================================================

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $products = Product::inRandomOrder()->limit(20)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($products as $product) {
            // 1-3 review acak per produk, dari user yang berbeda-beda.
            foreach ($users->random(min(3, $users->count())) as $user) {
                Review::updateOrCreate(
                    ['user_id' => $user->id, 'product_id' => $product->id],
                    ['rating' => fake()->numberBetween(3, 5), 'comment' => fake()->sentence(8)]
                );
            }
        }
    }
}
