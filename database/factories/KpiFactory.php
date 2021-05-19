<?php

namespace Database\Factories;

use App\Models\Kpi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KpiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kpi::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'kpi_no' => Str::random(10),
            'kpi_title' => $this->faker->text,
            'approve_status_id' => 1,
            'budget_year' => $this->faker->year
        ];
    }
}
