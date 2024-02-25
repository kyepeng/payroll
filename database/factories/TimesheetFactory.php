<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Timesheet;
use App\User;
use Faker\Generator as Faker;

$factory->define(Timesheet::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'date' => $faker->date(),
        'hours_worked' => $faker->numberBetween(1, 8),
        'description' => $faker->text
    ];
});
