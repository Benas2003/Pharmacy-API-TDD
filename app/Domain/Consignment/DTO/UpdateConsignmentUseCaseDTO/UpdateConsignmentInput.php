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
    private Authenticatable $auth;


    public function __construct(Request $request, int $id, Authenticatable $auth)
    {
        $consignmentRepository = new ConsignmentRepository();
        $consignmentValidator = new ConsignmentValidator();

        $consignmentValidator->validateStatus($request);

        $this->status = $request->status;
        $this->auth = $auth;
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
     * @return Authenticatable
     */
    public function getAuth(): Authenticatable
    {
        return $this->auth;
    }


}
