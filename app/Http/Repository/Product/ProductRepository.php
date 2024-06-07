<?php

declare(strict_types=1);

namespace App\Http\Repository\Product;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @param array<int,int> $productsIds
     *
     * @return Collection<Product>
     */
    public function getByIds(array $productsIds): Collection
    {
        return Product::whereIn('id', $productsIds)->get();
    }
}
