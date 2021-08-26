<?php

namespace App\Http\Controllers;

use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ConsignmentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $requested_products = $request->toArray();

        $consignment = Consignment::create([
            'department_name'=>Auth::user()->name,

        ]);

        foreach ($requested_products as $requested_product)
        {
            if(!$this->validateAmount($requested_product) || !$this->validateId($requested_product))
            {
                break;
            }

            $existing_product = Product::findOrFail($requested_product['id']);
            if($existing_product->amount>=$requested_product['amount'])
            {
                ConsignmentProduct::create([
                    'consignment_id' => $consignment->id,
                    'VSSLPR' => $existing_product->VSSLPR,
                    'name' => $existing_product->name,
                    'amount' => $requested_product['amount'],
                    'price'=>$requested_product['amount']*$existing_product->price,
                ]);
            }

        }

        $data = [
            "Consignment Info"=>$consignment,
            "Consignment Products:"=>ConsignmentProduct::where('consignment_id', $consignment->id)->get(),
        ];
        return response()->json($data,ResponseAlias::HTTP_CREATED);
    }

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
            'status'=>[
                'required',
                Rule::in(['Created', 'Processed', 'Given away']),
            ],
        ]);

        $consignment = Consignment::findOrFail($id);
        $consignment->update([
            'status'=>$request['status'],
        ]);

        return response()->json($consignment, ResponseAlias::HTTP_OK);
    }

    private function validateAmount(mixed $requested_product): bool
    {
        return $requested_product['amount'] > 0 && is_int($requested_product['amount']) && !empty($requested_product['amount']);
    }

    private function validateId(mixed $requested_product): bool
    {
        return $requested_product['id'] > 0 && is_int($requested_product['id']) && !empty($requested_product['id']);
    }
}
