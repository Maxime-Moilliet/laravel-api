<?php

declare(strict_types=1);

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<string|mixed>>
     */
    public function rules(): array
    {
        return [
            'ref' => ['required', Rule::unique('orders', 'ref')->ignore($this->order)],
            'customer_id' => ['exists:customers,id', 'required'],
            'items' => ['array', 'required'],
            'items.*.product_id' => ['exists:products,id', 'required'],
            'items.*.quantity' => ['integer', 'required', 'min:0'],
        ];
    }
}
