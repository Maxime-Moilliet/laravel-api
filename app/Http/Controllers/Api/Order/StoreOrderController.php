<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class StoreOrderController extends Controller
{
    public function __invoke(OrderRequest $request): JsonResponse
    {
        /** @var array<int, array{product_id: int, quantity: int}> $items */
        $items = $request->get('items');

        $priceExcludingVat = 0;
        $price = 0;
        $orderProduct = [];
        foreach ($items as $item) {
            /** @var Product $product */
            $product = Product::find($item['product_id']);

            $price += $product->price * $item['quantity'];
            $priceExcludingVat += $product->price_excluding_vat * $item['quantity'];

            $orderProduct[$product->id] = [
                'price' => $product->price,
                'vat' => $product->vat,
                'price_excluding_vat' => $product->price_excluding_vat,
                'quantity' => $item['quantity']
            ];
        }

        $order = Order::create([
            'ref' => $request->get('ref'),
            'status' => OrderStatusEnum::INCOMPLETE,
            'customer_id' => $request->get('customer_id'),
            'price_excluding_vat' => $priceExcludingVat,
            'price' => $price,
        ]);

        $order->products()->sync($orderProduct);

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(code: Response::HTTP_CREATED);
    }
}
