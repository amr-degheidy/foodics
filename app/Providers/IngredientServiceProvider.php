<?php

namespace App\Providers;

use App\Http\Repository\Ingredient\IngredientRepository;
use App\Http\Repository\Ingredient\IngredientRepositoryInterface;
use App\Http\Services\Ingredient\IngredientService;
use App\Http\Services\Ingredient\IngredientServiceInterface;
use Illuminate\Support\ServiceProvider;

class IngredientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

        $this->app->bind(
            IngredientServiceInterface::class,
            IngredientService::class
        );

        $this->app->bind(
            IngredientRepositoryInterface::class,
            IngredientRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
