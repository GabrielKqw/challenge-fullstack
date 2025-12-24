<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test listing all products (public route)
     */
    public function test_can_list_all_products(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id',
                         'name',
                         'description',
                         'price',
                         'quantity',
                         'active',
                     ]
                 ]);
    }

    /**
     * Test showing a single product (public route)
     */
    public function test_can_show_single_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 100.00,
            'quantity' => 50,
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $product->id,
                     'name' => 'Test Product',
                     'price' => 100.00,
                     'quantity' => 50,
                 ]);
    }

    /**
     * Test showing non-existent product returns 404
     */
    public function test_show_non_existent_product_returns_404(): void
    {
        $response = $this->getJson('/api/products/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Product not found'
                 ]);
    }

    /**
     * Test creating a product requires authentication
     */
    public function test_cannot_create_product_without_authentication(): void
    {
        $productData = [
            'name' => 'New Product',
            'description' => 'Product description',
            'price' => 99.99,
            'quantity' => 10,
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(401);
    }

    /**
     * Test creating a product with valid data (authenticated)
     */
    public function test_can_create_product_with_valid_data(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $productData = [
            'name' => 'New Product',
            'description' => 'Product description',
            'price' => 99.99,
            'quantity' => 10,
            'active' => true,
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(201)
                 ->assertJson([
                     'name' => 'New Product',
                     'price' => 99.99,
                     'quantity' => 10,
                 ]);

        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'price' => 99.99,
            'quantity' => 10,
        ]);
    }

    /**
     * Test creating a product with invalid data (validation failure)
     */
    public function test_cannot_create_product_with_invalid_data(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $productData = [
            'name' => '', // Nome vazio (inválido)
            'price' => -10, // Preço negativo (inválido)
            'quantity' => 'not-a-number', // Quantidade não numérica (inválida)
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'price', 'quantity']);
    }

    /**
     * Test updating a product requires authentication
     */
    public function test_cannot_update_product_without_authentication(): void
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product',
            'price' => 199.99,
            'quantity' => 20,
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test updating a product with valid data (authenticated)
     */
    public function test_can_update_product_with_valid_data(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $product = Product::factory()->create([
            'name' => 'Original Product',
            'price' => 100.00,
            'quantity' => 50,
        ]);

        $updateData = [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 199.99,
            'quantity' => 75,
            'active' => true,
        ];

        $response = $this->putJson("/api/products/{$product->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'name' => 'Updated Product',
                     'price' => 199.99,
                     'quantity' => 75,
                 ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'price' => 199.99,
            'quantity' => 75,
        ]);
    }

    /**
     * Test updating a product with invalid data (validation failure)
     */
    public function test_cannot_update_product_with_invalid_data(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $product = Product::factory()->create();

        $updateData = [
            'name' => '', // Nome vazio (inválido)
            'price' => 0, // Preço zero (inválido)
            'quantity' => -5, // Quantidade negativa (inválida)
        ];

        $response = $this->putJson("/api/products/{$product->id}", $updateData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'price', 'quantity']);
    }

    /**
     * Test deleting a product requires authentication
     */
    public function test_cannot_delete_product_without_authentication(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(401);
    }

    /**
     * Test deleting a product (authenticated)
     */
    public function test_can_delete_product(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Product deleted successfully'
                 ]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    /**
     * Test deleting non-existent product returns 404
     */
    public function test_delete_non_existent_product_returns_404(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/products/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Product not found'
                 ]);
    }
}

