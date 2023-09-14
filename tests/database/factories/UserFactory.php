<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Tests\Database\Factories;

use AMgrade\LaravelRoles\Tests\Models\User;
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
