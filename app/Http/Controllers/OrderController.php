<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'amount'=>'required|numeric|gt:0',
        ]);

        $order = Order::findOrFail($id);
        if($order->status === 'Ordered')
        {
            $order->update(['amount'=>$request['amount']]);
            return response()->json($order, ResponseAlias::HTTP_OK);
        }
        return response()->json('Requested amount change can not be performed when order was delivered', ResponseAlias::HTTP_METHOD_NOT_ALLOWED);


    }
}
