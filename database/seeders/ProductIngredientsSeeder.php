<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductIngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = Ingredient::pluck('id','name');

        $ingredientsQuantity = [
            'Beef'=> 150,
            'Cheese'=> 30,
            'Onion'=> 20,
        ];

        $product = Product::first();

        foreach ($ingredients as $ingredientName => $ingredientId) {
            $product->ingredients()->attach($ingredientId, ['quantity' => $ingredientsQuantity[$ingredientName]]);
        }

    }
}
