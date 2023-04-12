<?php

declare(strict_types=1);

namespace App\Dto\Customer;

use App\Dto\DtoInterface;
use Illuminate\Foundation\Http\FormRequest;

final class CustomerDto implements DtoInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $note,
    ) {
    }

    public static function make(FormRequest $request): CustomerDto
    {
        return new self(
            name: strval($request->get('name')),
            email: strval($request->get('email')),
            note: strval($request->get('note')),
        );
    }

    /**
     * @return array{name: string, email: string, note: string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'note' => $this->note,
        ];
    }
}
