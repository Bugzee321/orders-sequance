<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Order;
use App\Services\OrderService;

class ApproveOrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    /**
     * @OA\Post(
     *     path="/orders/{order}/approve",
     *     summary="Approve the given order",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order approved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Order approved successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Order does not need approval",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Order does not need approval")
     *         )
     *     )
     * )
     *
     * @param Order $order
     * @return void
     */
    public function __invoke(Order $order)
    {
        if (!$order->needsApproval()) {
            return ApiResponse::error('Order does not need approval', 400);
        }

        $this->orderService->approveOrder($order);

        return ApiResponse::success('Order approved successfully');
    }
}
