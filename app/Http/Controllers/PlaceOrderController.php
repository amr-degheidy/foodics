<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrderRequest;
use App\Http\Services\Order\OrderService;
use App\Http\Services\Order\OrderServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlaceOrderController extends Controller
{
    public function placeOrder(
        PlaceOrderRequest $request,
        OrderServiceInterface $orderService // for more usage us as class prop in constructor
    ): JsonResponse {
        $orderService->placeOrder($request->validated());
        return response()->json([
            'message'=> 'Order Created Successfully',
        ], JsonResponse::HTTP_CREATED);
    }
}
