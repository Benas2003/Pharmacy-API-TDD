<?php

namespace App\Domain\Consignment\DTO\CreateConsignmentUseCaseDTO;

use Illuminate\Support\Collection;


class CreateConsignmentInput
{
    private Collection $products;
    private int $userId;

    public function __construct(Collection $products, int $userId)
    {
        $this->products = $products;
        $this->userId = $userId;
    }

    /**
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
