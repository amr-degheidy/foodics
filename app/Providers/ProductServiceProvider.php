<?php

namespace App\Providers;

use App\Http\Repository\Product\ProductRepository;
use App\Http\Repository\Product\ProductRepositoryInterface;
use App\Http\Services\Product\ProductService;
use App\Http\Services\Product\ProductServiceInterface;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductServiceInterface::class,
            ProductService::class
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
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
