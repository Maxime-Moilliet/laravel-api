<?php

declare(strict_types=1);

namespace App\Dto\Auth;

use App\Dto\DtoInterface;
use Illuminate\Foundation\Http\FormRequest;

final class LoginDto implements DtoInterface
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }

    public static function make(FormRequest $request): LoginDto
    {
        return new self(
            email: strval($request->get(key: 'email')),
            password: strval($request->get(key: 'password')),
        );
    }
}
