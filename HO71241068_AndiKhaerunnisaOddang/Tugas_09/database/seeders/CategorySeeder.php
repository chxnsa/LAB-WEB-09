<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Elektronik',
            'description' => 'Produk elektronik seperti laptop, smartphone, dan aksesori'
        ]);

        Category::create([
            'name' => 'Fashion',
            'description' => 'Pakaian, sepatu, dan aksesoris fashion'
        ]);

        Category::create([
            'name' => 'Perabotan',
            'description' => 'Furniture dan perlengkapan rumah tangga'
        ]);

        Category::create([
            'name' => 'Makanan & Minuman',
            'description' => 'Produk makanan dan minuman kemasan'
        ]);
    }
}