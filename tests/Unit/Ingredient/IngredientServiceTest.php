<?php

namespace Tests\Unit\Ingredient;

use App\Http\Services\Ingredient\IngredientServiceInterface;
use App\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientServiceTest extends TestCase
{
    use RefreshDatabase;

    private IngredientServiceInterface $ingredientService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ingredientService = app(IngredientServiceInterface::class);

        $this->refreshDatabase();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_product_quantity_is_not_enough_to_order(): void
    {
        $result = $this->ingredientService->isProductIngredientsAvailable(
            [
                ['product_id' => Product::first()->id, 'quantity' => 3232],
            ]
        );
        $this->assertFalse($result);
    }
    /**
     * A basic feature test example.
     */
    public function test_product_quantity_is_enough_to_order(): void
    {

        $result = $this->ingredientService->isProductIngredientsAvailable(
            [
                ['product_id' => Product::first()->id, 'quantity' => 1],
            ]
        );

        $this->assertTrue($result);
    }


}
