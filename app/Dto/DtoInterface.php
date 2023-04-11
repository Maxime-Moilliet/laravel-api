<?php

declare(strict_types=1);

namespace App\Dto;

use Illuminate\Foundation\Http\FormRequest;

interface DtoInterface
{
    public static function make(
        FormRequest $request,
    ): self;
}
