<?php

namespace App\Http\Repository\Ingredient;

use App\Models\ProductIngredient;
use Illuminate\Support\Collection;

interface IngredientRepositoryInterface
{
    /**
     * @param array<int> $productIds
     *
     * @return Collection<ProductIngredient>
     */
    public function getProductIngredientsForProductIds(array $productIds): Collection;
}
