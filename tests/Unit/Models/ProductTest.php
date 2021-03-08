<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Probamos que un producto pertenezca a una categoria
     *
     * @return void
     */
    public function test_products_belongs_to_category()
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf( Category::class, $product->category );
    }
    /**
     * Pruebo que un producto pertenezca a un usuario
     */
    public function test_products_belongs_to_user()
    {
        $product = Product::factory()->create();
        
        $this->assertInstanceOf( User::class, $product->createdBy );
    }
}
