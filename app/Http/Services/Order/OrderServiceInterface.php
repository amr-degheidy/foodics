<?php

namespace App\Http\Services\Order;

use App\Models\Order;

interface OrderServiceInterface
{
    /**
     * @param array{products: array<int, array{product_id:int, quantity: int}>} $orderDetails
     */
    public function placeOrder(array $orderDetails): Order;
}
