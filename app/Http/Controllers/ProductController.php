<?php

namespace App\Http\Controllers;

use App\Domain\Product\Repository\ProductRepository;
use App\Domain\Product\Services\searchService;
use App\Domain\Product\Services\stockUpdateService;
use App\Domain\Product\UseCase\CreateProductUseCase;
use App\Domain\Product\UseCase\DestroyProductUseCase;
use App\Domain\Product\UseCase\UpdateProductUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $productRepository = new ProductRepository();
        return $productRepository->getAllProducts();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $createProductUseCase = new CreateProductUseCase($request);
        return $createProductUseCase->execute();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $productRepository = new ProductRepository();
        return $productRepository->getProductById($id);
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
        $updateProductUseCase = new UpdateProductUseCase($request);
        return $updateProductUseCase->execute($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $destroyProductUseCase = new DestroyProductUseCase();
        return $destroyProductUseCase->execute($id);
    }

    /**
     * Search for a name.
     *
     * @param  string  $name
     * @return JsonResponse
     */
    public function search(string $name): JsonResponse
    {
        $searchService = new searchService();
        return $searchService->searchByName($name);
    }

    /**
     * Update product stock
     *
     * @param  int  $id
     * @param  int  $amount
     */
    public function stockUpdate(int $id, int $amount): void
    {
        $stockUpdateService = new stockUpdateService();
        $stockUpdateService->execute($id, $amount);
    }
}
