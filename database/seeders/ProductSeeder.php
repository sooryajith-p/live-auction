<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 2) as $i) {
            $startingPrice = rand(1000, 5000);

            Product::firstOrCreate(
                ['title' => "Product $i"],
                [
                    'description' => "This is a description for Product $i.",
                    'starting_price' => $startingPrice,
                    'current_price' => $startingPrice,
                    'start_time' => now(),
                    'end_time' => now()->addHours(3),
                    'video_url' => "https://www.youtube.com/embed/wDchsz8nmbo?si=lcRCWKglB_Xyw2xg",
                ]
            );
        }
    }
}
