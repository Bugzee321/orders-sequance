<?php

namespace Tests\Feature;

use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderApproveControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService();
    }

    public function test_approve_order_with_pending_approval_status()
    {
        $order = $this->orderService->createOrder([
            'items' => [[
                'product_name' => 'Test',
                'quantity' => 1,
                'unit_price' => 1001
            ]]
        ]);

        $response = $this->postJson("/api/orders/{$order->id}/approve");
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => 'Order approved successfully'
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'approved'
        ]);
    }
    

    public function test_approve_order_with_approved_status()
    {
        $order = $this->orderService->createOrder([
            'items' => [[
                'product_name' => 'Test',
                'quantity' => 1,
                'unit_price' => 999
            ]]
        ]);

        $response = $this->postJson("/api/orders/{$order->id}/approve");
        $response->assertStatus(400);
        $response->assertJson(['message' => 'Order does not need approval']);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'approved'
        ]);
    }
    

}
