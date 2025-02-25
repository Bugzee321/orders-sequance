<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class OrdersController extends Controller
{
    /**
     * OrdersController constructor.
     *
     * @param OrderService $orderService
     */
    public function __construct(private OrderService $orderService)
    {
    }

    /**
     * Get all orders.
     *
     * @OA\Get(
     *     path="/orders",
     *     summary="Get all orders",
     *     tags={"Orders"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/OrderResource"))
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $orders = Order::all();
        return ApiResponse::success(OrderResource::collection($orders));
    }

    /**
     * Get an order by ID.
     *
     * @OA\Get(
     *     path="/orders/{id}",
     *     summary="Get an order by ID",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="order", ref="#/components/schemas/OrderResource")
     *             )
     *         )
     *     )
     * )
     *
     * @param Order $order
     * @return JsonResponse
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['items', 'history']);
        return ApiResponse::success(new OrderResource($order));
    }

    /**
     * Create an order.
     *
     * @OA\Post(
     *     path="/orders",
     *     summary="Create an order",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateOrderRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/OrderResource")
     *     )
     * )
     *
     * @param CreateOrderRequest $request
     * @return JsonResponse
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder($request->validated());
        return ApiResponse::success(new OrderResource($order), 'Order created successfully', 201);
    }
}
