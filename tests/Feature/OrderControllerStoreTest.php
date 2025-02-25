<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerStoreTest extends TestCase
{
    use RefreshDatabase;
    public function test_auto_approval()
    {
        // Test with $999 total
        $response = $this->postJson('/api/orders', [
            'items' => [[
                'product_name' => 'Test',
                'quantity' => 1,
                'unit_price' => 999
            ]]
        ]);
        $response->assertJson(['data' => ['status' => 'approved']]);
    }

    public function test_auto_approval_based_on_amount()
    {
         // Test with $1001 total
         $response = $this->postJson('/api/orders', [
            'items' => [[
                'product_name' => 'Test',
                'quantity' => 1,
                'unit_price' => 1001
            ]]
        ]);
        $response->assertJson(['data' => ['status' => 'pending_approval']]);
    }

}
