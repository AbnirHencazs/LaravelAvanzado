<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function products( Request $request, Product $product )
    {
        $request->user()->rate( $product, $request->score );

        return response()->json([
            'data' => 'califición aplicada'
        ]);
    }
}
