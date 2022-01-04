<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Tests\Database\Factories;

use McMatters\LaravelRoles\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class UserFactory
 *
 * @package McMatters\LaravelRoles\Tests\Database\Factories
 */
class UserFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = User::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
        ];
    }
}
