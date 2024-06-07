<?php

namespace App\Http\Services\Product;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    /**
     * @param array<int, array{product_id:int, quantity: int}> $productsDetails
     *
     * @return Collection<Product>
     */
    public function calculateTotalPricesForProducts(array $productsDetails): Collection;
}
