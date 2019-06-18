<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, User::rules());
    }

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 *
	 * @return mixed
	 * @throws \Throwable
	 */
    protected function create(array $data)
    {
    	return DB::transaction(function () use ($data) {
		    $user = User::create([
			    'name' => $data['name'],
			    'address' => $data['address'],
			    'phone' => $data['phone'], //Format phone before insert it in the database?
			    'email' => $data['email'],
			    'employee_id' => $data['employee_id'],
			    'password' => $data['password'],
			    'date_of_birth' => $data['date_of_birth'],
			    'in_probation' => isset($data['in_probation'])
		    ]);

		    $user->assignRole(Str::lower($data['role']));

		    return $user;
	    });
    }
}
