<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
	    'employee_id' => $faker->name,
	    'phone' => $faker->phoneNumber,
	    'address' => $faker->address,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
	    'date_of_birth' => $faker->date(),
	    'in_probation' => rand(0, 1),
        'remember_token' => Str::random(10),
    ];
});

//$factory->afterCreating(App\User::class, function ($user, $faker) {
//	$user->roles()->save(factory(Role::class)->make());
//});
