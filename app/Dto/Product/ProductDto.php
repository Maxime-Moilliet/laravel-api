<?php

declare(strict_types=1);

namespace App\Dto\Product;

use App\Dto\DtoInterface;
use Illuminate\Foundation\Http\FormRequest;

final class ProductDto implements DtoInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $ref,
        public readonly int $vat,
        public readonly int $priceExcludingVat,
    ) {
    }

    public static function make(FormRequest $request): ProductDto
    {
        return new self(
            name: strval($request->get('name')),
            ref: strval($request->get('ref')),
            vat: intval($request->get('vat')),
            priceExcludingVat: intval($request->get('price_excluding_vat')),
        );
    }

    /**
     * @return array{name: string, ref: string, vat: int, priceExcludingVat: int}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'ref' => $this->ref,
            'vat' => $this->vat,
            'priceExcludingVat' => $this->priceExcludingVat,
        ];
    }
}
