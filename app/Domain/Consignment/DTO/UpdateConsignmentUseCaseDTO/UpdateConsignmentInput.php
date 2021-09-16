<?php

namespace App\Domain\Consignment\DTO\UpdateConsignmentUseCaseDTO;

use App\Domain\Consignment\Repository\ConsignmentRepository;
use App\Domain\Consignment\Validator\ConsignmentValidator;
use App\Models\Consignment;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class UpdateConsignmentInput
{
    private Consignment $consignment;
    private string $status;
    private string $userName;

    public function __construct(Request $request, int $id, string $userName, ConsignmentRepository $consignmentRepository, ConsignmentValidator $consignmentValidator)
    {
        $consignmentValidator->validateStatus($request);

        $this->status = $request->status;
        $this->userName = $userName;
        $this->consignment = $consignmentRepository->getSpecificConsignment($id);
    }

    /**
     * @return Consignment
     */
    public function getConsignment(): Consignment
    {
        return $this->consignment;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }
}
