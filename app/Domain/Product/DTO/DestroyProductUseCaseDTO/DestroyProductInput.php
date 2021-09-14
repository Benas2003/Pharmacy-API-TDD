<?php

namespace App\Domain\Product\DTO\DestroyProductUseCaseDTO;

use App\Domain\Product\Validator\ProductValidator;
use App\Models\Product;
use Illuminate\Http\Request;

class DestroyProductInput
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
