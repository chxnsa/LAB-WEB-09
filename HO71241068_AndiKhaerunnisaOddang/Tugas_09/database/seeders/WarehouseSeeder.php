<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::create([
            'name' => 'Gudang Makassar',
            'location' => 'Jl. Perintis Kemerdekaan KM 10, Makassar, Sulawesi Selatan'
        ]);

        Warehouse::create([
            'name' => 'Gudang Gowa',
            'location' => 'Jl. Malino, Sungguminasa, Gowa, Sulawesi Selatan'
        ]);

        Warehouse::create([
            'name' => 'Gudang Maros',
            'location' => 'Jl. Trans Sulawesi, Maros, Sulawesi Selatan'
        ]);
    }
}