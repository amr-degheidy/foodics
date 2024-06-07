<?php

declare(strict_types=1);

namespace App\Http\Services\Order;

use App\Exceptions\OrderNotPlacedException;
use App\Http\Repository\Order\OrderRepositoryInterface;
use App\Http\Services\Ingredient\IngredientServiceInterface;
use App\Http\Services\Product\ProductServiceInterface;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ProductServiceInterface $productService,
        private readonly IngredientServiceInterface $ingredientService
    ) {
    }

    /**
     * @param array{products: array<int, array{product_id:int, quantity: int}>} $orderDetails
     */
    public function placeOrder(array $orderDetails): Order
    {
        DB::beginTransaction();

        try {
            $requestedOrderedProducts = $orderDetails['products'];
            // Create the order
            $productsWithTotalPrices = $this->productService->calculateTotalPricesForProducts($requestedOrderedProducts);

            $order = $this->orderRepository->createOrderWithProducts($productsWithTotalPrices);

            $this->ingredientService->decreaseIngredientsForGivenProductsDetails($requestedOrderedProducts);

            // Commit the transaction
            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new OrderNotPlacedException('Order not placed',500);
        }
    }
}
