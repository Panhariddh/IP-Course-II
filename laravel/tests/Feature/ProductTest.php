<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test ID: PRO-001
     * Description: Check if a product can be created
     */
    public function test_a_product_can_be_created(): void
    {
        $category = Category::create(['name' => 'Electronics']);

        $product = Product::create([
            'name' => 'Laptop',
            'category_id' => $category->id,
            'pricing' => 1000,
            'description' => 'A good laptop',
            'images' => [],
        ]);

        $this->assertDatabaseHas('products', ['name' => 'Laptop']);
    }

    /**
     * Test ID: PRO-002
     * Description: Check if we can access the get all products API
     */
    public function test_if_we_can_access_get_all_products_api(): void
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [ // each product item
                'id',
                'name',
                'pricing',
                'description',
                'images',
                'category_id',
                'category',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    /**
     * Test ID: PRO-003
     * Description: Check if we can retrieve a single product
     */
    public function test_get_single_product(): void
    {
        $category = Category::create(['name' => 'Accessories']);

        $product = Product::create([
            'name' => 'Mouse',
            'category_id' => $category->id,
            'pricing' => 25,
            'description' => 'Wireless mouse',
            'images' => [],
        ]);

        $response = $this->get("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $product->id,
                     'name' => 'Mouse',
                     'pricing' => 25,
                 ]);
    }

    /**
     * Test ID: PRO-004
     * Description: Check if we can update a product
     */
    public function test_update_product(): void
    {
        $category = Category::create(['name' => 'Books']);

        $product = Product::create([
            'name' => 'Book 1',
            'category_id' => $category->id,
            'pricing' => 10,
            'description' => 'Some description',
            'images' => [],
        ]);

        $response = $this->patch("/api/products/{$product->id}", [
            'name' => 'Updated Book',
            'pricing' => 15,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'message' => 'Product updated successfully'
                 ]);
    }

    /**
     * Test ID: PRO-005
     * Description: Check if we can delete a product
     */
    public function test_delete_product(): void
    {
        $category = Category::create(['name' => 'Clothing']);

        $product = Product::create([
            'name' => 'T-shirt',
            'category_id' => $category->id,
            'pricing' => 20,
            'description' => 'Cotton shirt',
            'images' => [],
        ]);

        $response = $this->delete("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Product deleted successfully']);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
