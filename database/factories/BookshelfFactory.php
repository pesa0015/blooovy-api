<?php

use App\Bookshelf;

$factory->define(Bookshelf::class, function (Faker\Generator $faker) {
    return [
        'book_id' => 1,
        'user_id' => 1
    ];
});
