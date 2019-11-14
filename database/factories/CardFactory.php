<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Card;
use App\CardCategory;
use Faker\Generator as Faker;

$factory->define(Card::class, function (Faker $faker) {
    $cardCategory = CardCategory::all()->random();
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'price' => $faker->numberBetween($min = 1000, $max = 9000),
        'cardCategory_id' => $cardCategory->id,
    ];
});
