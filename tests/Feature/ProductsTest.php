<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

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
        $product = Product::create([
            'name' => 'Product 1',
            'price_usd' => 123,
            'price_eur' => 111,
        ]);
        $response = $this->get('/products');

        $response->assertStatus(200);

        $response->assertDontSee(__('No products found'));

        $response->assertSee('Product 1');

        $response->assertViewHas('products', function ($collection) use ($product) {
            return $collection->contains($product);
        });
    }
}
