<?php

use App\Models\Companies\CompanyGroupUsers;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(CompanyGroupUsers::class, function () {
    return [
        'group_id' => 1,
        'user_id' => 1,
    ];
});
