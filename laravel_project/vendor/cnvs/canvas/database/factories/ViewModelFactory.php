<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Canvas\View::class, function (Faker\Generator $faker) {
    return [
        'post_id' => $faker->uuid,
        'ip' => $faker->ipv4,
        'agent' => $faker->userAgent,
        'referer' => $faker->url,
    ];
});
