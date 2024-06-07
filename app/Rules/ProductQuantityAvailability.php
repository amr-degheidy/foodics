<?php

namespace App\Rules;

use App\Http\Services\Ingredient\IngredientServiceInterface;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductIngredient;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductQuantityAvailability implements ValidationRule
{
    public function __construct(
        public IngredientServiceInterface $ingredientService
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$this->ingredientService->isProductIngredientsAvailable($value)) {
            $fail("Some of Our Ingredients is out of stock");
        }
    }
}
