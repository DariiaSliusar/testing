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

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->admin = $this->createUser(isAdmin: true);
    }

    /**
     * A basic feature test example.
     */
    public function testProductsPageContainEmptyTable(): void
    {
        $response = $this->actingAs($this->user)->get('/products');

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
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $response->assertDontSee(__('No products found'));

        $response->assertSee('Product 1');

        $response->assertViewHas('products', function ($collection) use ($product) {
            return $collection->contains($product);
        });
    }

    public function testPaginatedProductsTableDoesntContain11thRecord(): void
    {
        $products = Product::factory()->count(11)->create();
        $lastProduct = $products->last();

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
            return !$collection->contains($lastProduct);
        });
    }

    public function testAdminCanSeeProductCreateButton()
    {
        $response = $this->actingAs($this->admin)->get('/products');

        $response->assertStatus(200);

        $response->assertSee('Add Product');
    }

    public function testNonAdminCannotSeeProductCreateButton()
    {
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $response->assertDontSee('Add Product');
    }

    public function testAdminCanAccessProductPage()
    {
        $response = $this->actingAs($this->admin)->get('/products/create');

        $response->assertStatus(200);
    }

    public function testNonAdminCannotAccessProductPage()
    {
        $response = $this->actingAs($this->user)->get('/products/create');

        $response->assertStatus(403);
    }

    public function testCreateProductSuccessful()
    {
        $product = [
            'name' => 'Product 123',
            'price_usd' => 100,
            'price_eur' => 90,
        ];
        $response = $this->actingAs($this->admin)->post('/products', $product);

        $response->assertStatus(302);

        $response->assertRedirect('/products');

        $this->assertDatabaseHas('products', $product);

        $lastProduct = Product::latest()->first();

        $this->assertEquals($product['name'], $lastProduct->name);
        $this->assertEquals($product['price_usd'], $lastProduct->price_usd);
        $this->assertEquals($product['price_eur'], $lastProduct->price_eur);
    }

    public function testProductEditContainCorrectValues()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->get('products/' . $product->id . '/edit');

        $response->assertStatus(200);

        $response->assertSee('value="' . $product->name . '"', false);
        $response->assertSee('value="' . $product->price_usd . '"', false);
        $response->assertSee('value="' . $product->price_eur . '"', false);

        $response->assertViewHas('product', $product);
    }

    public function testProductUpdateValidationErrorRedirectBackToForm()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->put('products/' . $product->id, [
            'name' => '',
            'price_usd' => '',
            'price_eur' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
        $response->assertInvalid(['name', 'price_usd', 'price_eur'] );
    }

    private function createUser(bool $isAdmin = false): User
    {
        return User::factory()->create([
            'is_admin' => $isAdmin,
        ]);
    }
}
