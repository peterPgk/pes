<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function showRegistrationForm()
    {
        //Fetch all available roles form database
        $roles = Role::all();

        return view('auth.register', compact('roles'));
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $checkboxAllowedValues = [1, '1', 'on', true];

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
        return Validator::make($data, [
            'name' => 'required|string|min:3|max:255',
            'address' => 'nullable|string|min:3|max:255',
            'phone' => 'nullable|phone:GB', //TODO: Provide custom country field, ana attach it with address field
            'email' => 'required|string|email|max:255|unique:users',
            'employee_id' => 'nullable|unique:users|alpha_num',
            'password' => ['required', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', 'confirmed'],
            'date_of_birth' => ['nullable', 'date', 'before:'. Carbon::now()->subYears(10)->toDateString()],
            'in_probation' => 'sometimes|in:'. implode(',', $this->checkboxAllowedValues),
            'role' => 'required|exists:roles,name'
        ]);
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
			    'password' => Hash::make($data['password']),
			    'date_of_birth' => $data['date_of_birth'],
			    'in_probation' => (bool)(isset($data['in_probation']) && in_array($data['in_probation'], $this->checkboxAllowedValues))
		    ]);

		    $user->assignRole(Str::lower($data['role']));

		    return $user;
	    });
    }
}
