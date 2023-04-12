<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

final class StoreOrUpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'note' => 'required',
        ];
    }
}
