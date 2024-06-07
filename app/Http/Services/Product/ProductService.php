<?php

declare(strict_types=1);

namespace App\Http\Services\Product;

use App\Http\Repository\Product\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Collection;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * @param array<int, array{product_id:int, quantity: int}> $productsDetails
     *
     * @return Collection<Product>
     */
    public function calculateTotalPricesForProducts(array $productsDetails): Collection
    {
        $productsDetails = collect($productsDetails);
        $productsIds = $productsDetails->pluck('product_id')->toArray();

        return
            $this->productRepository->getByIds($productsIds)
                 ->each(function ($product) use ($productsDetails) {
                     $product->requiredQuantity = $productsDetails->firstWhere('product_id', $product->id)['quantity'];
                     $product->totalPriceForProduct = $product->price * $product->requiredQuantity;
            })
        ;
    }
}
