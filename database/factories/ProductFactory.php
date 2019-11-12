<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Laravel\Models\Category;
use App\Laravel\Models\Products;
use Faker\Generator as Faker;

$factory->define(Products::class, function (Faker $faker) {
    $category = Category::all()->random();
    return [
        'name' => $faker->sentence($nbWords = 2, $variableNbWords = true),
        'brand' => $faker->company,
        'description' => $faker->realText($maxNbChars = 300),
        'price' => $faker->numberBetween($min = 1000, $max = 9000),
        'ratings' => $faker->numberBetween($min = 0, $max = 5),
        'category_id' => $category->id,
        'quantity' => $faker->numberBetween($min = 1, $max = 1000),
        'date_of_purchase' => $faker->date($formate = 'Y-m-d'),
        'condition' => $faker->randomElement(['new', 'moderate', 'used', 'old', 'not_working']),
        'reviewed' => $faker->boolean,
        'available_in' => implode(',', $faker->randomElements(['Surat', 'Pune', 'Banglore', 'Delhi'], $count = $faker->numberBetween($min = 0, $max = 3))),
    ];
});
