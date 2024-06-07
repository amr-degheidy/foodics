<?php

declare(strict_types=1);

namespace App\Http\Repository\Order;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @param Collection<Product> $products
     */
    public function createOrderWithProducts(Collection $products): Order
    {

        $order =  Order::create([
            'total_price' => $products->sum('totalPriceForProduct'),
        ]);

        foreach ($products as $product) {
            $order->details()
                ->attach($product->id, [
                    'quantity' => $product->requiredQuantity,
                    'price' => $product->totalPriceForProduct
                ]);
        }

        return $order;
    }

}
