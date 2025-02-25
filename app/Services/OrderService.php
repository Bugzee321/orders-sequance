<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Sequence;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Generate a new order number.
     *
     * @return string
     */
    public function generateOrderNumber(): string
    {
        return DB::transaction(function () {
            $sequence = Sequence::lockForUpdate()->where('type', 'order')->first();
            if (!$sequence) {
                $sequence = Sequence::create(['type' => 'order', 'last_number' => 0]);
            }
            $sequence->increment('last_number');
            return 'ORD-' . str_pad($sequence->last_number, 6, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Calculate the total amount for the given items.
     *
     * @param array $items
     * @return float
     */
    public function calculateTotal(array $items): float
    {
        return collect($items)->sum(fn($item) => $item['quantity'] * $item['unit_price']);
    }

    /**
     * Create a new order with the given data.
     *
     * @param array $data
     * @return Order
     */
    public function createOrder(array $data): Order
    {
        $total = $this->calculateTotal($data['items']);
        $order = DB::transaction(function () use ($data, $total) {
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'total' => $total,
            ]);

            foreach ($data['items'] as $item) {
                $order->items()->create($item);
            }

            return $order;
        });
        return $order;
    }

    /**
     * Approve the given order.
     *
     * @param Order $order
     * @return void
     */
    public function approveOrder(Order $order): void
    {
        $order->update(['status' => 'approved']);
    }
}
