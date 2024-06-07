<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            ['name'=> 'Beef' , 'quantity' => 20 * 1000],
            ['name'=> 'Cheese' , 'quantity' => 5 * 1000],
            ['name'=> 'Onion' , 'quantity' => 1 * 1000],
        ];

        \DB::table('ingredients')->insert($ingredients);
    }
}
