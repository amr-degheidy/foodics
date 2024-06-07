<?php

namespace App\Models;

use AllowDynamicProperties;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    public float $totalPriceForProduct; // this for purpose usage for dynamic properties
    public int $requiredQuantity; // this for purpose usage for dynamic properties
    protected $guarded = [];

    public function ingredients(): BelongsToMany
    {
        return  $this->belongsToMany(Ingredient::class,'product_ingredients');
    }
}
