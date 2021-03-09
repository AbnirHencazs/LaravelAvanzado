<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_product_can_be_rated_by_user()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $user->rate( $product, 5 );

        $this->assertInstanceOf( Collection::class, $user->ratings(Product::class)->get() );
        $this->assertInstanceOf( Collection::class, $product->qualifiers(User::class)->get() );
    }

    public function test_averageRating()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create();

        $user->rate( $product, 5 );
        $user2->rate( $product, 10 );

        $this->assertEquals( 7.5, $product->averageRating( User::class) );
    }

    public function test_rating_model()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $user->rate( $product, 5 );

        $rating = Rating::first();

        $this->assertInstanceOf( Product::class, $rating->rateable );
        $this->assertInstanceOf( User::class, $rating->qualifier );
    }
}
