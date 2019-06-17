<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'phone', 'email', 'employee_id', 'password', 'date_of_birth', 'in_probation'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


	public static function rules(): array
	{
		return [
			'name' => 'required|string|min:3|max:255',
			'address' => 'nullable|string|min:3|max:255',
			'phone' => 'nullable|phone:GB', //TODO: Provide custom country field, ana attach it with address field
			'email' => 'required|string|email|max:255|unique:users',
			'employee_id' => 'nullable|unique:users|alpha_num',
			'password' => ['required', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', 'confirmed'],
			'date_of_birth' => ['nullable', 'date', 'before:'. Carbon::now()->subYears(10)->toDateString()],
			'in_probation' => 'sometimes|in:'. implode(',', config('validation.checkbox_allowed_values')),
			'role' => 'required|exists:roles,name'
		];
	}
}
