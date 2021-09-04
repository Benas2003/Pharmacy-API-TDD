<?php

namespace App\Http\Controllers;

use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\InvoiceItem;
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
            'department_id'=>Auth::user()->id,

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
                'product_id' => $existing_product->id,
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
            'Department name' => User::select('name')->where('id', $consignment->department_id)->get(),
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
     * @throws BindingResolutionException
     * @throws Exception
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


        if($request['status'] === 'Processed')
        {
            $consignment_products = ConsignmentProduct::where('consignment_id',$id)->get();
            foreach ($consignment_products as $consignment_product)
            {
                $storage_product = Product::findOrFail($consignment_product->product_id);

                if($storage_product->amount === 0)
                {
                    return response()->json("Requested amount can not be given away because $storage_product->name amount in storage equals 0", ResponseAlias::HTTP_METHOD_NOT_ALLOWED);
                }

            }

            foreach ($consignment_products as $consignment_product)
            {

                $storage_product = Product::findOrFail($consignment_product->product_id);

                if($storage_product->amount>=$consignment_product->amount)
                {
                    $storage_product->update([
                        'amount'=>$storage_product->amount-$consignment_product->amount,
                    ]);
                }else {
                    $consignment_product->update([
                        'amount'=>$storage_product->amount
                    ]);
                    $storage_product->update([
                        'amount'=>0
                    ]);
                }

            }
            $invoice = $this->generateInvoice($consignment, $consignment_products);
            $invoice->download();
        }

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

    private function generateInvoice($consignment, $consignment_products): Invoice
    {
        $customer = new Party([
            'name' => User::select('name')->where('id', $consignment->department_id)->get(),
            'date' => $consignment->created_at,
        ]);

        $seller = new Party([
            'pharmacist' => Auth::user()->name,
        ]);

        foreach ($consignment_products as $product) {
            $consignment_items[] = (new InvoiceItem())->title($product->VSSLPR . ' | ' . $product->name)->pricePerUnit($product->price / $product->amount)->quantity($product->amount);
        }

        return Invoice::make("Consignment - #$consignment->id")
            ->template('consignment_template')
            ->series('EUR-INT-C')
            ->sequence($consignment->id)
            ->serialNumberFormat('{SERIES}{SEQUENCE}')
            ->seller($seller)
            ->buyer($customer)
            ->dateFormat('Y/m/d')
            ->currencySymbol('€')
            ->currencyCode('EUR')
            ->addItems($consignment_items)
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator("'")
            ->currencyDecimalPoint('.')
            ->filename('Consignment' . '-000' . $consignment->id)
            ->logo(public_path('vendor/invoices/logo.png'))
            ->save('public');
    }
}
