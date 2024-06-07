<?php

namespace App\Observers;

use App\Http\Services\Ingredient\IngredientServiceInterface;
use App\Models\Ingredient;

class IngredientObserver
{
    public function __construct(
        private readonly IngredientServiceInterface $ingredientService
    ){
    }

    /**
     * Handle the Ingredient "updated" event.
     */
    public function updated(Ingredient $ingredient): void
    {
        if($ingredient->isIngredientReachedMinTarget()) {
            $this->ingredientService->notifyIngredientReachedLimit($ingredient);
        }
    }

}
