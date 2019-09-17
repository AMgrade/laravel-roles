<?php

declare(strict_types = 1);

use Faker\Generator;
use McMatters\LaravelRoles\Tests\Models\User;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(User::class, static function (Generator $faker) {
    return [
        'name' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $faker->password,
    ];
});
