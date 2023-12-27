<?php

declare(strict_types=1);

namespace AMgrade\Roles\Tests\Database\Factories;

use AMgrade\Roles\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
        ];
    }
}
