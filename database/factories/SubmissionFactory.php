<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'patient_id' => User::factory(),
            'doctor_id' => User::factory(),
            'weigth' => $this->faker->numberBetween(40, 250),
            'heigth' => $this->faker->numberBetween(150, 210),
            'observations' => $this->faker->text(),
            'symptons' => $this->faker->text(),
            'file_path' => $this->faker->text(),
            'status' => $this->faker->word(),
        ];
    }
}