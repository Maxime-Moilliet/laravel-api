<?php

declare(strict_types=1);

namespace App\Traits;

trait CalculatePriceTrait
{
    public function calculatePrice(
        int $priceExcludingVat,
        int $vat,
    ): int
    {
        return intval($priceExcludingVat + ($priceExcludingVat * ($vat / 100)));
    }
}
