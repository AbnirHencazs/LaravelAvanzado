<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs( User::factory()->create() );
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        Product::factory(5)->create();

        $response = $this->getJson( '/api/products' );

        $response
            ->assertSuccessful()
            ->assertHeader('content-type', 'application/json')
            ->assertJsonCount(5, 'data'); //cuenta los datos en la key 'data'
    }

    public function test_store_product()
    {
        $data = [
            'name' => 'Hola',
            'price' => 1000
        ];

        $this
            ->postJson( '/api/products', $data )
            ->assertSuccessful()
            ->assertHeader( 'content-type', 'application/json' );

        $this
            ->assertDatabaseHas( 'products', $data );
    }

    public function test_udpate_product()
    {
        $product = Product::factory()->create();
        $data = [
            'name' => 'Upddate produtc',
            'price' => 20000
        ];

        $this
            ->patchJson( "/api/products/{$product->getKey()}", $data )
            ->assertSuccessful()
            ->assertHeader( 'content-type', 'application/json' );
    }

    public function test_show_product()
    {
        $product = Product::factory()->create();
        
        $this
            ->getJson( "/api/products/{$product->getKey()}" )
            ->assertSuccessful()
            ->assertHeader( 'content-type', 'application/json' );
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();

        $this
            ->deleteJson( "/api/products/{$product->getKey()}" )
            ->assertSuccessful()
            ->assertHeader( 'content-type', 'application/json' );

        $this
            ->assertDatabaseMissing( 'products', [
                'name' => $product->name,
                'price' => $product->price
            ] );
    }
}
