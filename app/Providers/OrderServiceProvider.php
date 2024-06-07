<?php

namespace App\Providers;

use App\Http\Repository\Order\OrderRepository;
use App\Http\Repository\Order\OrderRepositoryInterface;
use App\Http\Services\Order\OrderService;
use App\Http\Services\Order\OrderServiceInterface;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            OrderServiceInterface::class,
            OrderService::class
        );
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
