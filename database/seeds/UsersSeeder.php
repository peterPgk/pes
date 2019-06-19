<?php

use App\User;
use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: pgk
 * Date: 6/17/19
 * Time: 9:34 AM
 */

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'password' => env('MANAGER_PASSWORD'),
            'email' => env('MANAGER_EMAIL'),
        ])->assignRole('manager');

        factory(User::class)->create([
            'password' => env('STAFF_PASSWORD'),
            'email' => env('STAFF_EMAIL'),
        ])->assignRole('staff member');


    }
}