<?php

namespace App\Http\Controllers;

use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ConsignmentProductsController extends Controller
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

        $product = ConsignmentProduct::findOrFail($id);
        $consignment = Consignment::find($product->consignment_id);

        if($consignment->status === 'Created')
        {
            $product->update(['amount'=>$request['amount']]);
            return response()->json($product, ResponseAlias::HTTP_OK);
        }

        return response()->json('Requested amount change can not be performed when consignment enters "Processed" status', ResponseAlias::HTTP_METHOD_NOT_ALLOWED);


    }
}
