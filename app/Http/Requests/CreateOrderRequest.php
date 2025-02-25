<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateOrderRequest",
 *     type="object",
 *     @OA\Property(property="items", type="array", @OA\Items(
 *         @OA\Property(property="product_name", type="string", example="Sample Product"),
 *         @OA\Property(property="quantity", type="integer", example=1),
 *         @OA\Property(property="unit_price", type="number", format="float", example=9.99)
 *     ))
 * )
 */
class CreateOrderRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0.01']
        ];
    }
}
