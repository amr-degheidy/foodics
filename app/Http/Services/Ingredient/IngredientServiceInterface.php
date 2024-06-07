<?php

namespace App\Http\Services\Ingredient;

use App\Models\Ingredient;
use Illuminate\Support\Collection;

interface IngredientServiceInterface
{
    /**
     * @param array<int, array{product_id:int, quantity: int}> $productsDetails
     */
    public function isProductIngredientsAvailable(array $productsDetails): bool;

    /**
     * @param array<int, array{product_id:int, quantity: int}> $productsDetails
     *
     * @return Collection<Ingredient>
     */
    public function getProductIngredientsWithTotalNeededQuantityForAllProductsIds(
        array $productsDetails,
    ): Collection;

    /**
     * @param array<int, array{product_id:int, quantity: int}> $productsDetails
     */
    public function decreaseIngredientsForGivenProductsDetails(array $productsDetails): void;

    public function notifyIngredientReachedLimit(Ingredient $ingredient): void;

    public function addMoreIngredients(Ingredient $ingredient, int $newQuantity): void;
}
