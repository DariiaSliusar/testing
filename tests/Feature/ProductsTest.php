<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{

    /**
     * A basic feature test example.
     */
    public function testProductsPageContainEmptyTable(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);

        $response->assertSee(__('No products found'));
    }

    public function testProductsPageContainNonEmptyTable(): void
    {
        Product::create([
            'name' => 'Test Product',
            'price' => 99,
        ]);
        $response = $this->get('/products');

        $response->assertStatus(200);

        $response->assertDontSee(__('No products found'));
    }
}
