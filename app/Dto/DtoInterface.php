<?php

namespace App\Dto;

use Illuminate\Foundation\Http\FormRequest;

interface DtoInterface
{
    public static function make(
        FormRequest $request,
    ): self;
}
