<?php

namespace App\Domain\Consignment\DTO\DestroyConsignmentUseCaseDTO;

class DestroyConsignmentInput
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
