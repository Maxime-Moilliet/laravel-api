<?php

declare(strict_types=1);

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ProductRequest extends FormRequest
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
            'name' => ['required'],
            'ref' => ['required', Rule::unique('products', 'ref')->ignore($this->product)],
            'vat' => ['required', 'integer'],
            'price_excluding_vat' => ['required', 'integer'],
        ];
    }
}
