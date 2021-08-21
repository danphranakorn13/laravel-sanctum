<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user(); // get user login current

        // check role = 1 (admin)
        if ($user->tokenCan('1'))
        {
            $request->validate([
                'name'  => 'required',
                'slug'  => 'required',
                'price' => 'required|between:0,9999999.99',
            ]);

            return Product::create($request->all());

        }
        else
        {
            return response([
                "message" => "Forbidden",
                "func"    => "store",
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return Product::find($product->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $user = auth()->user(); // get user login current

        // check role = 1 (admin)
        if ($user->tokenCan('1'))
        {
            $request->validate([
                'name'  => 'required',
                'slug'  => 'required',
                'price' => 'required|between:0,9999999.99',
            ]);

            return Product::where('id', $product->id)
                ->update($request->all());

        }
        else
        {
            return response([
                "message" => "Forbidden",
                "func"    => "update",
            ], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $user = auth()->user(); // get user login current

        // check role = 1 (admin)
        if ($user->tokenCan('1'))
        {
            return Product::where('id', $product->id)->delete();
        }
        else
        {
            return response([
                "message" => "Forbidden",
                "func"    => "update",
            ], 403);
        }

    }
}
