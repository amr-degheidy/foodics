<?php

namespace App\Http\Repository\Order;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    /**
     * @param Collection<Product> $products
     */
    public function createOrderWithProducts(Collection $products): Order;
}
