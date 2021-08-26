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
         * Display a listing of the resource.
         *
         * @return Consignment[]|Collection
         */
    public function index(): Collection|array
    {
        return Consignment::all();
    }

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
            ConsignmentProduct::create([
                'consignment_id' => $consignment->id,
                'VSSLPR' => $existing_product->VSSLPR,
                'name' => $existing_product->name,
                'amount' => $requested_product['amount'],
                'price'=>$requested_product['amount']*$existing_product->price,
            ]);

        }

        $data = [
            "Consignment Info"=>$consignment,
            "Consignment Products:"=>ConsignmentProduct::where('consignment_id', $consignment->id)->get(),
        ];
        return response()->json($data,ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $consignment = Consignment::findOrFail($id);
        $consignment_products = ConsignmentProduct::where('consignment_id',$id)->get();

        $data = [
            'Department name' => $consignment->department_name,
            'Status'=>$consignment->status,
            'Products:'=>$consignment_products,
        ];
        return response()->json($data, ResponseAlias::HTTP_OK);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $consignment = Consignment::findOrFail($id);

        if($consignment->status === 'Processed')
        {
            return response()->json('Requested action can not be performed because consignment entered "Processed" status', ResponseAlias::HTTP_METHOD_NOT_ALLOWED);
        }
        $consignment->delete();
        return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
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
