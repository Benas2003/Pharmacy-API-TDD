<?php

namespace App\Domain\Product\DTO\StockUpdateServiceDTO;

class StockUpdateInput
{
    private int $id;
    private int $amount;

    public function __construct(int $id, int $amount)
    {
        $this->id = $id;
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
}
