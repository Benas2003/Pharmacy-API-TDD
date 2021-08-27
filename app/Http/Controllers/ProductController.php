<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Prophecy\Doubler\Generator\Node\ArgumentNode;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Product[]
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
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
     * @return JsonResponse
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
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'VSSLPR'=>'required|starts_with:VSSLPR',
            'name'=>'required',
            'storage_amount'=>'required|numeric|gt:0',
            'price'=>'required|numeric|gt:0',
        ]);

        $product = Product::find($id);
        $product->update($request->all());
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(null, 204);
    }

    /**
     * Search for a name.
     *
     * @param  string  $name
     * @return Response
     */
    public function search($name): Response
    {

        return Product::where('name', 'like', '%'.$name.'%')->get();
    }

    /**
     * Update product stock
     *
     * @param  int  $id
     * @param  string  $order_unique_code
     * @return JsonResponse
     */
    public function stockUpdate(int $id, string $order_unique_code): JsonResponse
    {
        $product = Product::findOrFail($id);
        $order = Order::where('EUR_INT_O', $order_unique_code)->get()->first();

        $product->update([
            'amount'=>$product->amount+$order->amount,
        ]);

        $order->update([
            'status'=>'Delivered',
        ]);

        return response()->json($product, 200);
    }
}
