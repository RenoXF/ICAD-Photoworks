<?php

namespace Database\Seeders;

use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'title' => 'Eco Shooting Wedding',
            'type' => ProductType::WeddingShooting,
            'description' => "- Max duration 7 hours continously\n- 2 video cameras\n - 2 LED TV 40\"\n- HD 1080p quality\n- File in flashdisk",
            'price' => '3000000'
        ]);

        Product::create([
            'title' => 'Basic Shooting Wedding',
            'type' => ProductType::WeddingShooting,
            'description' => "- Max duration 7 hours continously\n- 2 video cameras\n - 2 LED TV 40\"\n- HD 1080p quality\n- File in flashdisk",
            'price' => '3800000'
        ]);
    }
}
