<?php

namespace Tests;

use App\User;
use Drfraker\SnipeMigrations\SnipeMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, SnipeMigrations;

    protected $user;

    protected function loginWithFakeUser(array $userData = [], string $role = ''): User
    {
        $this->user = factory(User::class)->create($userData);
        $this->actingAs($this->user, 'web');
        if ($role !== '') {
            $this->user->assignRole($role);
        }

        return $this->user;
    }
}
