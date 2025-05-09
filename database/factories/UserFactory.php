<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory {
    public $model = User::class;

    public function definition(): array {
        return [
            'id'          => $this->faker->uuid(),
            'last_name'   => $this->faker->lastName(),
            'first_name'  => $this->faker->firstName(),
            'middle_name' => $this->faker->lastName(),
            'email'       => $this->faker->unique()->safeEmail(),
            'phone'       => $this->faker->phoneNumber(),
            'password'    => bcrypt('123'),
        ];
    }
}
