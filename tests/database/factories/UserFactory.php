<?php

declare(strict_types = 1);

/** @var Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator;
use McMatters\LaravelRoles\Tests\Models\User;

$factory->define(User::class, function (Generator $faker) {
    return [
        'name' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $faker->password,
    ];
});
