<?php

namespace App\Http\Controllers;

use App\Domain\Consignment\DTO\CreateConsignmentUseCaseDTO\CreateConsignmentInput;
use App\Domain\Consignment\DTO\DestroyConsignmentUseCaseDTO\DestroyConsignmentInput;
use App\Domain\Consignment\DTO\UpdateConsignmentUseCaseDTO\UpdateConsignmentInput;
use App\Domain\Consignment\Exceptions\InvalidProductInformationInputException;
use App\Domain\Consignment\Repository\ConsignmentRepository;
use App\Domain\Consignment\UseCase\CreateConsignmentUseCase;
use App\Domain\Consignment\UseCase\DestroyConsignmentUseCase;
use App\Domain\Consignment\UseCase\UpdateConsignmentUseCase;
use App\Domain\Consignment\Validator\ConsignmentValidator;
use App\Domain\Product\Repository\ProductRepository;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ConsignmentController extends Controller
{
    private ConsignmentRepository $consignmentRepository;
    private CreateConsignmentUseCase $createConsignmentUseCase;
    private DestroyConsignmentUseCase $destroyConsignmentUseCase;
    private UpdateConsignmentUseCase $updateConsignmentUseCase;
    private ConsignmentValidator $consignmentValidator;
    private ProductRepository $productRepository;

    public function __construct(
        ConsignmentRepository $consignmentRepository,
        CreateConsignmentUseCase $createConsignmentUseCase,
        DestroyConsignmentUseCase $destroyConsignmentUseCase,
        UpdateConsignmentUseCase $updateConsignmentUseCase,
        ConsignmentValidator $consignmentValidator,
        ProductRepository $productRepository
    )
    {
        $this->consignmentRepository = $consignmentRepository;
        $this->createConsignmentUseCase = $createConsignmentUseCase;
        $this->destroyConsignmentUseCase = $destroyConsignmentUseCase;
        $this->updateConsignmentUseCase = $updateConsignmentUseCase;
        $this->consignmentValidator = $consignmentValidator;
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return new JsonResponse($this->consignmentRepository->getAllConsignments());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $products = $this->getRequestedProducts($request);

        $createConsignmentInput = new CreateConsignmentInput($products, Auth::user()->id);
        return new JsonResponse($this->createConsignmentUseCase->execute($createConsignmentInput)->toArray(), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return new JsonResponse($this->consignmentRepository->getConsignmentById($id)->toArray());
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
        $updateConsignmentInput = new UpdateConsignmentInput($request, $id, Auth::user()->name, new ConsignmentRepository(), new ConsignmentValidator());

        $this->updateConsignmentUseCase->execute($updateConsignmentInput);
        return new JsonResponse($this->updateConsignmentUseCase->execute($updateConsignmentInput)->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $destroyConsignmentInput = new DestroyConsignmentInput($id);
        $this->destroyConsignmentUseCase->execute($destroyConsignmentInput);
        return new JsonResponse(null, 204);
    }

    private function getRequestedProducts(Request $request): Collection
    {
        $products = collect();
        $requested_products = $request->toArray();
        foreach ($requested_products as $requested_product) {
            if (!$this->consignmentValidator->validateAmount($requested_product) || !$this->consignmentValidator->validateId($requested_product)) {
                throw new InvalidProductInformationInputException();
            }
            $product = $this->productRepository->getProductById($requested_product['id']);
            $product->amount = $requested_product['amount'];
            $products->push($product);
        }
        return $products;
    }
}
