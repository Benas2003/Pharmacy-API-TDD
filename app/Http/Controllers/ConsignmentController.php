<?php

namespace App\Http\Controllers;

use App\Domain\Consignment\Repository\ConsignmentRepository;
use App\Domain\Consignment\UseCase\CreateConsignmentUseCase;
use App\Domain\Consignment\UseCase\DestroyConsignmentUseCase;
use App\Domain\Consignment\UseCase\UpdateConsignmentUseCase;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $consignmentRepository = new ConsignmentRepository();
        return $consignmentRepository->getAllProducts();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $createConsignmentUseCase = new CreateConsignmentUseCase($request);
        return $createConsignmentUseCase->execute();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $consignmentRepository = new ConsignmentRepository();
        return $consignmentRepository->getProductById($id);
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
        $updateConsignmentUseCase = new UpdateConsignmentUseCase($request);
        return $updateConsignmentUseCase->execute($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $destroyConsignmentUseCase = new DestroyConsignmentUseCase();
        return $destroyConsignmentUseCase->execute($id);
    }
}
