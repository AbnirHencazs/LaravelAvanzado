<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class CategoryControllerTest extends TestCase
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
        Category::factory(5)->create();

        $response = $this->getJson( '/api/categories' );

        $response
            ->assertSuccessful()
            ->assertHeader('content-type', 'application/json')
            ->assertJsonCount(5, 'data');
    }

    public function test_store_category()
    {
        $data = [
            'name' => 'Hola'
        ];

        $this
            ->postJson( '/api/categories', $data )
            ->assertSuccessful()
            ->assertHeader( 'content-type', 'application/json' );

        $this
            ->assertDatabaseHas( 'categories', $data );
    }

    public function test_udpate_category()
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'Update category',
        ];

        $this
            ->patchJson( "/api/categories/{$category->getKey()}", $data )
            ->assertSuccessful()
            ->assertHeader( 'content-type', 'application/json' );
    }

    public function test_show_category()
    {
        $category = Category::factory()->create();
        
        $this
            ->getJson( "/api/categories/{$category->getKey()}" )
            ->assertSuccessful()
            ->assertHeader( 'content-type', 'application/json' );
    }

    public function test_delete_category()
    {
        $category = Category::factory()->create();

        $this
            ->deleteJson( "/api/categories/{$category->getKey()}" )
            ->assertSuccessful()
            ->assertHeader( 'content-type', 'application/json' );

        $this
            ->assertDatabaseMissing( 'products', [
                'name' => $category->name
            ] );
    }
}
