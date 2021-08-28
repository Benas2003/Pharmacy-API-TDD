<?php

namespace Database\Factories;

use App\Models\Consignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsignmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Consignment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'department_name'=> $this->faker->numerify('LSMULKK###'),
            'status' => array_rand(['Created', 'Processed', 'Given away']),
        ];
    }
}
