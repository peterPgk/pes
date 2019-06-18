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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase {

	use RefreshDatabase;

	public function testEdit()
	{

	}

	public function testIndex()
	{

//		$users = factory(User::class, 10)->create()->each(function ($user) {
//			$user->roles
//		});

		$response = $this->get('/users');

		dd($response->decodeResponseJson());
	}

	public function testUpdate() {

	}

	public function testDestroy() {

	}

	public function testCreate() {

	}

	public function testStore() {

	}
}
