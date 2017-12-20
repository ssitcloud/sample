<?php

use Faker\Generator as Faker;
//use App\Models\Status;

$factory->define(App\Models\Status::class, function (Faker $faker) {
    $date_time = $faker->date . ' ' . $faker->time;
    return [
        //生产模拟数据
        'content'    => $faker->text(),
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});