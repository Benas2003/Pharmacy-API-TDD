<?php

namespace App\Domain\Consignment\DTO\CreateConsignmentUseCaseDTO;

use App\Domain\Consignment\Exceptions\InvalidProductInformationInputException;
use App\Domain\Consignment\Validator\ConsignmentValidator;
use App\Domain\Product\Repository\ProductRepository;
use App\Models\Product;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class CreateConsignmentInput
{
    private Collection $products;
    private Authenticatable $auth;

    public function __construct(Request $request, Authenticatable $auth)
    {
        $this->products = collect();
        $productRepository = new ProductRepository();
        $consignmentValidator = new ConsignmentValidator();

        $requested_products = $request->toArray();
        foreach ($requested_products as $requested_product)
        {
            if (!$consignmentValidator->validateAmount($requested_product) || !$consignmentValidator->validateId($requested_product)) {
                throw new InvalidProductInformationInputException();
            }
            $product = $productRepository->getProductById($requested_product['id']);
            $product->amount = $requested_product['amount'];
            $this->products->push($product);
        }

        $this->auth = $auth;

    }

    /**
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @return Authenticatable
     */
    public function getAuth(): Authenticatable
    {
        return $this->auth;
    }


}
