<?php

namespace Tests\Feature;

use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerIndexTest extends TestCase
{
    use RefreshDatabase;

    protected $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService();
    }
    public function test_index_returns_all_orders()
    {

        $order1 = $this->orderService->createOrder([
            'items' => [[
                'product_name' => 'Test',
                'quantity' => 1,
                'unit_price' => 100
            ]]
        ]);

        $order2 = $this->orderService->createOrder([
            'items' => [[
                'product_name' => 'Test',
                'quantity' => 1,
                'unit_price' => 200
            ]]
        ]);

        $order3 = $this->orderService->createOrder([
            'items' => [[
                'product_name' => 'Test',
                'quantity' => 1,
                'unit_price' => 2000
            ]]
        ]);

        $response = $this->getJson('/api/orders');
        $response->assertJson([
            'status' => 'success',
            'message' => null,
            'data' => [
                ['order_number' => $order1->order_number, 'total' => '100.00', 'status' => 'approved'],
                ['order_number' => $order2->order_number, 'total' => '200.00', 'status' => 'approved'],
                ['order_number' => $order3->order_number, 'total' => '2000.00', 'status' => 'pending_approval']
            ]
        ]);
    }
    
}
