<?php

namespace App;

use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Str;

/**
 * Class User
 * @package App
 *
 * @property int|string id
 * @property string name
 * @property string role
 * @property Collection roles
 * @property string address
 * @property string phone
 * @property string employee_id
 * @property string password
 * @property string|Carbon date_of_birth
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * Automatically load `roles` relation when fetching User model/entity
     * This is not always good practice, but for our case will work
     *
     * @var array
     */
    protected $with = ['roles'];

    /**
     *
     * @var array
     */
    protected $appends = ['role'];

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
        'in_probation' => 'boolean',
    ];

    /**
     * Rules for validating this object
     *
     * @return array
     */
	public static function rules(): array
	{
		return [
			'name' => 'required|string|min:3|max:255',
			'address' => 'nullable|string|min:3|max:255',
			'phone' => 'nullable|phone:GB', //TODO: Provide custom country field, ana attach it with address field
			'email' => 'required|string|email|max:255|unique:users',
			'employee_id' => 'nullable|min:3|unique:users,employee_id|alpha_num',
			'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|confirmed',
			'date_of_birth' => 'nullable|date|before:'. Carbon::now()->subYears(10)->toDateString(),
			'in_probation' => 'sometimes|in:'. implode(',', config('validation.checkbox_allowed_values')),
			'role' => 'required|exists:roles,name'
		];
	}

    /**
     * Accessor when we want user role.
     * Spatie plugin uses names instead of ids, so we need name
     *
     * We suppose that each user can have only one role
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        return $this->roles->first()->name;
	}

    /**
     * We handle password update, if in update user pass empty field, we
     * keep the old one
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value ? Hash::make($value) : $this->password;
	}

    /**
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
    public static function addWithRole(array $data)
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
