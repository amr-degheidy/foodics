<?php

namespace App\Http\Requests;

use App\Rules\ProductQuantityAvailability;
use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(ProductQuantityAvailability $productQuantityAvailability): array
    {
        return [
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products' => ['required', 'array',$productQuantityAvailability ]
        ];
    }
}
