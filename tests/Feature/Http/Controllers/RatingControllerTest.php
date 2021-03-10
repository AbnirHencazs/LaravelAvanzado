<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RatingControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Pruebo un End-Point de mi API que me permita calificar un producto
     *
     * @return void
     */
    public function test_api_product_rating()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $data = [
            'score' => 3.5
        ];

        $this
            ->actingAs( $user )
            ->postJson( "api/rating/products/$product->id", $data );

        $this
            ->assertDatabaseHas( 'ratings', [
                'score' => $data['score'],
                'rateable_type' => get_class($product),
                'rateable_id' => $product->id,
                'qualifier_type' => get_class( $user ),
                'qualifier_id' => $user->id
            ] );
    }
}
