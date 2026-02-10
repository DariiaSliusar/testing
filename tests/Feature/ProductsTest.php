<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
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
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);

        $response->assertSee(__('No products found'));
    }

    public function testProductsPageContainNonEmptyTable(): void
    {
        $user = User::factory()->create();

        $product = Product::create([
            'name' => 'Product 1',
            'price_usd' => 123,
            'price_eur' => 111,
        ]);
        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);

        $response->assertDontSee(__('No products found'));

        $response->assertSee('Product 1');

        $response->assertViewHas('products', function ($collection) use ($product) {
            return $collection->contains($product);
        });
    }

    public function testPaginatedProductsTableDoesntContain11thRecord(): void
    {
        $user = User::factory()->create();
        $products = Product::factory()->count(11)->create();
        $lastProduct = $products->last();

        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);

        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
            return !$collection->contains($lastProduct);
        });
    }
}
