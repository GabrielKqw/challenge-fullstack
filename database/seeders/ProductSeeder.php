<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Playstation 5',
            'description' => 'Reproduza jogos do PS5 e do PS4 em Blu-ray Disc. Você também pode baixar jogos do PS5 e do PS4 digitais a partir da PlayStation Store.',
            'price' => 3550.00,
            'quantity' => 100,
            'active' => true,
        ]);

        Product::create([
            'name' => 'Xbox Series X',
            'description' => 'O Xbox Series X oferece desempenho verdadeiramente de próxima geração com processador personalizado AMD Zen 2 e RDNA 2.',
            'price' => 3499.00,
            'quantity' => 85,
            'active' => true,
        ]);

        Product::create([
            'name' => 'Nintendo Switch',
            'description' => 'Console híbrido que permite jogar em casa na TV ou em movimento no modo portátil.',
            'price' => 1999.00,
            'quantity' => 150,
            'active' => true,
        ]);

        Product::factory()->count(20)->create();
    }
}

