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

    /** @test */
	public function only_manager_can_see_users()
	{
//	    $this->refreshDatabase();

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

    /** @test */
    public function staff_member_cannot_update_others_users_data()
    {
//        $this->refreshDatabase();

        //this will disable Laravel default exception handling
        $this->withoutExceptionHandling();

        $this->expectException('Spatie\Permission\Exceptions\UnauthorizedException');

        $user = factory(User::class)->create([
            'name' => 'Fake user',
            'password' => 'Test987',
            'address' => 'Some address',
            'email' => 'test@email.com',
            'phone' => '+441454808658',
            'employee_id' => 'ABCD12',
            'date_of_birth' => '2008-10-14'
        ])->assignRole($this->roles->first()->name);

        $this->loginWithFakeUser([], 'staff member');

        $this->put('/users/'. $user->id, $this->user->toArray());
    }

    /** @test */
	public function staff_member_can_update_his_data_with_some_limitations()
    {
//        $this->refreshDatabase();

        //To be sure, that validation will pass
        $this->loginWithFakeUser([
            'name' => 'Fake user again',
            'password' => 'Test987',
            'email' => 'fake_user_email@email.com',
            'phone' => '+441454808658',
            'employee_id' => 'ABCD123',
            'date_of_birth' => '2008-10-14'
        ], 'staff member');

        $oldInProbation = $this->user->in_probation;

        $myData = $this->user->toArray();
        $myData['name'] = 'New name';
        $myData['email'] = 'fake_user_email_new@test.com';
        $myData['in_probation'] = ! $oldInProbation; //trying to cheat
        $myData['role'] = 'manager'; //trying to cheat

        $this->put('/users/'. $this->user->id, $myData)->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => 'New name',
            'email' => 'fake_user_email_new@test.com',
            'in_probation' => $oldInProbation       //This should not be updated
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Fake user',
            'email' => 'fake_user_email@email.com'
        ]);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $this->roles->first(function ($role) {
                return $role->name === 'staff member';
            })->id,
            'model_id' => $this->user->id,
            'model_type' => User::class
        ]);

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $this->roles->first(function ($role) {
                return $role->name === 'manager';
            })->id,
            'model_id' => $this->user->id,
            'model_type' => User::class
        ]);

	}

    /** @test */
    public function manager_can_update_others_users_data()
    {
//        $this->refreshDatabase();

        $user = factory(User::class)->create([
            'name' => 'Fake user one',
            'password' => 'Test987',
            'address' => 'Some address',
            'email' => 'enail_fake@email.com',
            'phone' => '+441454808658',
            'employee_id' => 'ABCD1234',
            'date_of_birth' => '2008-10-14'
        ])->assignRole($this->roles->first()->name);

        $userData = $user->toArray();

        $oldInProbation = $userData['in_probation'];

        $userData['name'] = 'New name';
        $userData['email'] = 'enail_fake_new@email.com';
        $userData['in_probation'] = ! $oldInProbation;
        $userData['role'] = $this->roles->last()->name;

        $this->loginWithFakeUser([], 'manager');

        $this->put('/users/'. $user->id, $userData)->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => 'New name',
            'email' => 'enail_fake_new@email.com',
            'in_probation' => ! $oldInProbation
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Fake user one',
            'email' => 'enail_fake@email.com'
        ]);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $this->roles->last()->id,
            'model_id' => $this->user->id,
            'model_type' => User::class
        ]);

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $this->roles->first()->id,
            'model_id' => $this->user->id,
            'model_type' => User::class
        ]);
    }

    /** @test  */
	public function only_manager_can_delete_users()
    {
//        $this->refreshDatabase();

        $user = factory(User::class)->create()->assignRole($this->roles->first()->name);

        $this->assertDatabaseHas('users', ['email' => $user->email, 'name' => $user->name]);
        $this->assertDatabaseHas('model_has_roles', ['role_id' => $this->roles->first()->id, 'model_id' => $user->id, 'model_type' => User::class]);

        $this->loginWithFakeUser([], 'staff member');

        $this->delete('/users/'. $user->id)->assertStatus(403);

        $this->assertDatabaseHas('users', ['email' => $user->email, 'name' => $user->name]);
        $this->assertDatabaseHas('model_has_roles', ['role_id' => $this->roles->first()->id, 'model_id' => $user->id, 'model_type' => User::class]);

        $this->loginWithFakeUser([], 'manager');

        $this->delete('/users/'. $user->id)->assertStatus(302);

        $this->assertDatabaseMissing('users', ['email' => $user->email, 'name' => $user->name]);
        $this->assertDatabaseMissing('model_has_roles', ['role_id' => $this->roles->first()->id, 'model_id' => $user->id, 'model_type' => User::class]);
    }

    /** @test  */
	public function only_manager_can_create_users()
    {
//        $this->refreshDatabase();

        // Correct data
        $userData = [
            'name' => 'Fake user',
            'password' => 'Test987',
            'password_confirmation' => 'Test987',
            'address' => 'Some address',
            'email' => 'original@email.com',
            'role' => 'manager',
            'phone' => '+441454808658',
            'employee_id' => 'ABCD12',
            'date_of_birth' => '2008-10-14'
        ];

        $this->assertDatabaseMissing('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'phone' => $userData['phone']
        ]);

        $this->loginWithFakeUser([], 'staff member');

        $this->post('/users', $userData)->assertStatus(403);
        $this->assertDatabaseMissing('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'phone' => $userData['phone']
        ]);

        $this->loginWithFakeUser([], 'manager');
        $this->post('/users', $userData)->assertStatus(302);
//        $this->assertDatabaseHas('users', [
//            'name' => $userData['name'],
//            'email' => $userData['email'],
//            'phone' => $userData['phone']
//        ]);
	}
}
