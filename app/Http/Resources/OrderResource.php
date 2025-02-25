<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="OrderResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="order_number", type="string"),
 *     @OA\Property(property="total", type="number"),
 *     @OA\Property(property="status", type="string"),
 *     @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/OrderItemResource")),
 *     @OA\Property(property="history", type="array", @OA\Items(ref="#/components/schemas/OrderHistoryResource"))
 * )
 */

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'total' => $this->total,
            'status' => $this->status,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'history' => OrderHistoryResource::collection($this->whenLoaded('history')),
        ];
    }
}
