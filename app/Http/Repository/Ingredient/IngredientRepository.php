<?php

declare(strict_types=1);

namespace App\Http\Repository\Ingredient;

use App\Models\ProductIngredient;
use Illuminate\Support\Collection;

class IngredientRepository implements IngredientRepositoryInterface
{
    /**
     * @param array $productIds
     *
     * @return Collection<ProductIngredient>
     */
    public function getProductIngredientsForProductIds(array $productIds): Collection
    {
       return ProductIngredient::whereIn('product_id', $productIds)
            ->with('ingredient')
            ->get()
       ;
    }
}
