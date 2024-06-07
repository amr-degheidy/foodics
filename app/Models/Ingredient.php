<?php

namespace App\Models;

use App\Observers\IngredientObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy([IngredientObserver::class])] // can inside service provider too
class Ingredient extends Model
{
    use HasFactory;

    public int $totalNeededIngredientsQuantityForAllProducts; // for dynamic access
    protected $fillable = ['name', 'quantity', 'used_quantity'];

    public function products(): BelongsToMany
    {
        return  $this->belongsToMany(Product::class,'product_ingredients');
    }

    public function isIngredientReachedMinTarget(): bool
    {
        $currentUsedPercentage = ($this->used_quantity / ($this->quantity + $this->used_quantity) ) * 100;

        if($currentUsedPercentage >= env('MIN_INGREDIENTS_NOTIFICATION_PERCENTAGE')) {
            return true;
        }
        return false;
    }
}
