<?php

namespace Tests\Unit\Models;

use App\Models\Product;
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
}
