<?php

namespace App\Http\Repository\Product;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    /**
     * @param array<int,int> $productsIds
     *
     * @return Collection<Product>
    */
    public function getByIds(array $productsIds): Collection;
}
