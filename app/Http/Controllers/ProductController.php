<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\Rule;
use Prophecy\Doubler\Generator\Node\ArgumentNode;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'VSSLPR'=>'required|starts_with:VSSLPR',
            'name'=>'required',
            'storage_amount'=>'required|numeric|gt:0',
            'price'=>'required|numeric|gt:0',
        ]);

        $product = Product::create($request->all());
        return response()->json($product,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::find($id);
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'VSSLPR'=>'required|starts_with:VSSLPR',
            'name'=>'required',
            'amount'=>'required|numeric|gte:0',
            'storage_amount'=>'required|numeric|gt:0',
            'price'=>'required|numeric|gt:0',
            'status'=>[
                'required',
                Rule::in(['Active', 'Inactive']),
            ],
        ]);

        $product = Product::find($id);
        $product->update($request->all());
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(null, 204);
    }
}
