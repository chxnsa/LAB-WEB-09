<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $elektronik = Category::where('name', 'Elektronik')->first();
        $fashion = Category::where('name', 'Fashion')->first();
        
        $gudangMakassar = Warehouse::where('name', 'Gudang Makassar')->first();
        $gudangGowa = Warehouse::where('name', 'Gudang Gowa')->first();

        // Produk 1: Laptop ASUS
        DB::transaction(function () use ($elektronik, $gudangMakassar, $gudangGowa) {
            $product = Product::create([
                'name' => 'Laptop ASUS ROG',
                'price' => 15000000,
                'category_id' => $elektronik->id
            ]);

            $product->detail()->create([
                'description' => 'Laptop gaming dengan processor Intel Core i7, RAM 16GB, SSD 512GB',
                'weight' => 2.5,
                'size' => '15.6 inch'
            ]);

            $product->warehouses()->attach($gudangMakassar->id, ['quantity' => 15]);
            $product->warehouses()->attach($gudangGowa->id, ['quantity' => 10]);
        });

        // Produk 2: Smartphone Samsung
        DB::transaction(function () use ($elektronik, $gudangMakassar) {
            $product = Product::create([
                'name' => 'Samsung Galaxy S23',
                'price' => 12000000,
                'category_id' => $elektronik->id
            ]);

            $product->detail()->create([
                'description' => 'Smartphone flagship dengan kamera 50MP, RAM 8GB, Storage 256GB',
                'weight' => 0.3,
                'size' => '6.1 inch'
            ]);

            $product->warehouses()->attach($gudangMakassar->id, ['quantity' => 25]);
        });

        // Produk 3: Sepatu Nike
        DB::transaction(function () use ($fashion, $gudangGowa) {
            $product = Product::create([
                'name' => 'Nike Air Max',
                'price' => 1500000,
                'category_id' => $fashion->id
            ]);

            $product->detail()->create([
                'description' => 'Sepatu olahraga dengan teknologi air cushioning untuk kenyamanan maksimal',
                'weight' => 0.8,
                'size' => 'UK 9 / EU 43'
            ]);

            $product->warehouses()->attach($gudangGowa->id, ['quantity' => 50]);
        });

        DB::transaction(function () use ($elektronik, $gudangMakassar, $gudangGowa) {
            $product = Product::create([
                'name' => 'Logitech MX Master 3',
                'price' => 1200000,
                'category_id' => $elektronik->id
            ]);

            $product->detail()->create([
                'description' => 'Mouse wireless premium dengan sensor 4000 DPI dan baterai tahan lama',
                'weight' => 0.15,
                'size' => 'Standard'
            ]);

            $product->warehouses()->attach($gudangMakassar->id, ['quantity' => 30]);
            $product->warehouses()->attach($gudangGowa->id, ['quantity' => 20]);
        });

        DB::transaction(function () use ($fashion, $gudangMakassar) {
            $product = Product::create([
                'name' => 'Tas Ransel Eiger',
                'price' => 450000,
                'category_id' => $fashion->id
            ]);

            $product->detail()->create([
                'description' => 'Tas ransel dengan kapasitas 25L, material waterproof, cocok untuk hiking',
                'weight' => 0.6,
                'size' => '25 Liter'
            ]);

            $product->warehouses()->attach($gudangMakassar->id, ['quantity' => 40]);
        });
    }
}