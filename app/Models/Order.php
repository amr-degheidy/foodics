<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function details(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'order_details',
            'order_id',
            'product_id')
            ->withPivot('quantity,price');
    }
}
