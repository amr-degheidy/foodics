<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('products')->insert([
            'name'=> 'Burger',
            'description'=> 'Classic Burger Beef',
            'price'=> '100',
        ]);

    }
}
