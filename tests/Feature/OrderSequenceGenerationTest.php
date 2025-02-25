<?php

namespace Tests\Feature;

use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderSequenceGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected $orderService;

    protected function setUp(): void
    {
    parent::setUp();
       $this->orderService = new OrderService();
    }
    public function test_order_sequence_generation()
    {
        $num1 = $this->orderService->generateOrderNumber();
        $num2 = $this->orderService->generateOrderNumber();
        
        $this->assertEquals('ORD-000001', $num1);
        $this->assertEquals('ORD-000002', $num2);
    }
}
