<?php


use App\Models\Product;

beforeEach(function () {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
});

test('products page contain empty table', function () {
    $this->actingAs($this->user)
        ->get('/products')
        ->assertStatus(200)
        ->assertSee(__('No products found'));
});

test('products page contain non empty table', function () {
    $product = Product::create([
        'name' => 'Product 1',
        'price_usd' => 123,
        'price_eur' => 111,
    ]);
    $this->actingAs($this->user)->get('/products')
        ->assertStatus(200)
        ->assertDontSee(__('No products found'))
        ->assertSee('Product 1')
        ->assertViewHas('products', function ($collection) use ($product) {
        return $collection->contains($product);
    });
});

test('create product successful', function () {
    $product = [
        'name' => 'Product 123',
        'price_usd' => 100,
        'price_eur' => 90,
    ];
    $this->actingAs($this->admin)
        ->post('/products', $product, ['Accept' => 'text/html'])
        ->assertRedirect(route('products.index'));

    $this->assertDatabaseHas('products', $product);

    $lastProduct = Product::latest()->first();

    expect($lastProduct->name)->toBe($product['name']);
    expect($lastProduct->price_usd)->toBe($product['price_usd']);
    expect($lastProduct->price_eur)->toBe($product['price_eur']);
});
