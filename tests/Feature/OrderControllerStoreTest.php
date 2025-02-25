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

    public function test_order_validation_rules()
    {
        // Test empty items
        $response = $this->postJson('/api/orders', ['items' => []]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items']);

        // Test invalid item structure
        $response = $this->postJson('/api/orders', [
            'items' => [
                ['product_name' => 'Test', 'quantity' => -1, 'unit_price' => 0]
            ]
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'items.0.quantity',
                'items.0.unit_price'
            ]);

        // Test missing required fields
        $response = $this->postJson('/api/orders', [
            'items' => [
                ['quantity' => 1, 'unit_price' => 100] // missing product_name
            ]
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items.0.product_name']);
    }

    public function test_order_total_calculation()
    {
        // Test simple calculation
        $response = $this->postJson('/api/orders', [
            'items' => [
                ['product_name' => 'Item 1', 'quantity' => 2, 'unit_price' => 10.50],
                ['product_name' => 'Item 2', 'quantity' => 3, 'unit_price' => 20.25]
            ]
        ]);

        $expectedTotal = (2 * 10.50) + (3 * 20.25);
        $response->assertStatus(201)
            ->assertJsonPath('data.total', $expectedTotal);
    }
}
