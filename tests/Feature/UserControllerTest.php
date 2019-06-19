<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 18.6.2019 г.
 * Time: 20:25
 */

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\User;
use Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase {

	use RefreshDatabase;

	protected $roles;

	protected function setUp(): void
    {
        parent::setUp();

        //Seed roles
        Artisan::call('db:seed', ['--class' => 'RolesSeeder']);

        $this->roles = Role::all();
    }

    public function testEdit()
	{

	}

	public function testIndex()
	{
	    factory(User::class, 10)->create()->each(function ($user) {
	        $user->assignRole($this->roles->random()->name);
        });

	    //Take this here instead of returned set from factory f-n, because we have extra
        //two users (staff-member and manager, created through login process.
	    $users = User::all();

        $this->loginWithFakeUser([], 'staff member');
        $this->get('/users')->assertStatus(403);

        $this->loginWithFakeUser([], 'manager');
        $response = $this->get('/users')->assertStatus(200);

        $users->pluck('email')->each(function ($email) use ($response) {
            $response->assertSee($email);
        });
    }

	public function testUpdate()
    {
        $this->loginWithFakeUser([], 'staff member');
	}

	public function testDestroy()
    {

	}

	public function testStore()
    {
        $userData = factory(User::class)->make([

        ]);
        $userData['roles'] = $this->roles->first();

        $userArray = $userData->toArray();
        $userArray['password'] = 'Test987';
        $userArray['password_confirmation'] = 'Test987';

        $usera = [
            'name' => 'Fake user',
            'password' => 'Test987',
            'address' => 'Some address',
            'email' => 'test@email.com',
            'password_confirmation' => 'Test987',
            'role' => 'manager',
            'phone' => '+441454808658',
            'employee_id' => 'ABCD12',
            'date_of_birth' => '2008-10-14'
        ];

        $this->assertDatabaseMissing('users', ['name' => $userData->name, 'email' => $userData->email, 'phone' => $userData->phone]);

        $this->loginWithFakeUser([], 'staff member');

        $this->post('/users', $userArray)->assertStatus(403);

        $this->loginWithFakeUser([], 'manager');

        $response = $this->post('/users', $usera)->assertStatus(200);

//        dd($response);

	}
}
