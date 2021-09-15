<?php

namespace App\Http\Controllers;

use App\Domain\Product\DTO\CreateProductUseCaseDTO\CreateProductInput;
use App\Domain\Product\DTO\DestroyProductUseCaseDTO\DestroyProductInput;
use App\Domain\Product\DTO\StockUpdateServiceDTO\StockUpdateInput;
use App\Domain\Product\DTO\UpdateProductUseCaseDTO\UpdateProductInput;
use App\Domain\Product\Repository\ProductRepository;
use App\Domain\Product\Services\SearchService;
use App\Domain\Product\Services\StockUpdateService;
use App\Domain\Product\UseCase\CreateProductUseCase;
use App\Domain\Product\UseCase\DestroyProductUseCase;
use App\Domain\Product\UseCase\UpdateProductUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductRepository $productRepository;
    private CreateProductUseCase $createProductUseCase;
    private UpdateProductUseCase $updateProductUseCase;
    private DestroyProductUseCase $destroyProductUseCase;
    private SearchService $searchService;
    private StockUpdateService $stockUpdateService;

    public function __construct(ProductRepository $productRepository, CreateProductUseCase $createProductUseCase, UpdateProductUseCase $updateProductUseCase, DestroyProductUseCase $destroyProductUseCase, SearchService $searchService, StockUpdateService $stockUpdateService)
    {
        $this->productRepository = $productRepository;
        $this->createProductUseCase = $createProductUseCase;
        $this->updateProductUseCase = $updateProductUseCase;
        $this->destroyProductUseCase = $destroyProductUseCase;
        $this->searchService = $searchService;
        $this->stockUpdateService = $stockUpdateService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        return new JsonResponse($this->productRepository->getAllProducts());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $createProductInput = new CreateProductInput($request);
        return new JsonResponse($this->createProductUseCase->execute($createProductInput)->toArray(), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return new JsonResponse($this->productRepository->getProductById($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $updateProductInput = new UpdateProductInput($id, $request);
        return new JsonResponse($this->updateProductUseCase->execute($updateProductInput)->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $destroyProductInput = new DestroyProductInput($id);
        $this->destroyProductUseCase->execute($destroyProductInput);
        return new JsonResponse(null, 204);
    }

    /**
     * Search for a name.
     *
     * @param  string  $name
     * @return JsonResponse
     */
    public function search(string $name): JsonResponse
    {
        return new JsonResponse($this->searchService->searchByName($name));
    }

    /**
     * Update product stock
     *
     * @param  int  $id
     * @param  int  $amount
     */
    public function stockUpdate(int $id, int $amount): void
    {
        $stockUpdateInput = new StockUpdateInput($id, $amount);
        $this->stockUpdateService->execute($stockUpdateInput);
    }
}
