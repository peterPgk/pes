<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Created by PhpStorm.
 * User: pgk
 * Date: 6/17/19
 * Time: 9:34 AM
 */

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('permission.cache');

        foreach (config('permission.roles') as $role) {
            Role::findOrCreate($role);
        }
    }
}