<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductIngredient extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ingredient(): BelongsTo {
        return $this->belongsTo(Ingredient::class);
    }

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
