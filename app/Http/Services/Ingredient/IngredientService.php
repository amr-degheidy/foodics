<?php

declare(strict_types=1);

namespace App\Http\Services\Ingredient;

use App\Http\Repository\Ingredient\IngredientRepositoryInterface;
use App\Mail\IngredientsLimitReachedMail;
use App\Models\Ingredient;
use App\Models\ProductIngredient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class IngredientService implements IngredientServiceInterface
{

    public function __construct(
        private readonly IngredientRepositoryInterface $ingredientRepository
    ) {
    }

    /**
     * @param array<int, array{product_id:int, quantity: int}> $productsDetails
     */
    public function isProductIngredientsAvailable(array $productsDetails): bool
    {

        $ingredients = $this->getProductIngredientsWithTotalNeededQuantityForAllProductsIds($productsDetails);
        foreach($ingredients as $ingredient) {

            if($ingredient->quantity < $ingredient->totalNeededIngredientsQuantityForAllProducts) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array<int, array{product_id:int, quantity: int}> $productsDetails
     *
     * @return Collection<Ingredient>
     */
    public function getProductIngredientsWithTotalNeededQuantityForAllProductsIds(
        array $productsDetails,
    ): Collection {
        $totalIngredientsQuantity =[];

        $productsDetails = collect($productsDetails);
        $productsIds = $productsDetails->pluck('product_id')->toArray();

        return
            $this->ingredientRepository
                ->getProductIngredientsForProductIds($productsIds)
                ->each(function ($productIngredient) use ($productsDetails, &$totalIngredientsQuantity) {
                    $this->calculateTotalIngredientQuantityForAllProducts(
                        $productIngredient,
                        $productsDetails,
                        $totalIngredientsQuantity
                    );
                })
                ->pluck('ingredient')
                ->unique()
                ->each(function ($ingredient)  use (&$totalIngredientsQuantity) {
                    $ingredient->totalNeededIngredientsQuantityForAllProducts = $totalIngredientsQuantity[$ingredient->id];
                })
        ;
    }
    private function calculateTotalIngredientQuantityForAllProducts(
        ProductIngredient $productIngredient,
        Collection $productsDetails,
        array &$totalIngredientsQuantity
    ): void {
        $ingredientPerProductQuantity = $productIngredient->quantity * $productsDetails->firstWhere('product_id', $productIngredient->product_id)['quantity'];
        if(isset($totalIngredientsQuantity[$productIngredient->ingredient_id])) {
            $totalIngredientsQuantity[$productIngredient->ingredient_id] += $ingredientPerProductQuantity;
            return;
        }
            $totalIngredientsQuantity[$productIngredient->ingredient_id] = $ingredientPerProductQuantity;
    }

    public function decreaseIngredientsForGivenProductsDetails(array $productsDetails): void
    {
        $ingredients = $this->getProductIngredientsWithTotalNeededQuantityForAllProductsIds($productsDetails);

        foreach($ingredients as $ingredient) {

            $totalIngredients = $ingredient->totalNeededIngredientsQuantityForAllProducts;
            unset($ingredient->totalNeededIngredientsQuantityForAllProducts);

            $ingredient
                ->update([
                'quantity' => $ingredient->quantity - $totalIngredients,
                'used_quantity' => $ingredient->used_quantity + $totalIngredients
            ]);
        }
    }

    public function notifyIngredientReachedLimit(Ingredient $ingredient): void
    {
        Mail::to(env('MAIL_FROM_ADDRESS'))->queue(new IngredientsLimitReachedMail($ingredient));
    }

    public function addMoreIngredients(Ingredient $ingredient, int $newQuantity): void
    {
        // after adding new ingredient
        // will set used_ingredient to zero
        // to start calculate from the beginning
    }
}
