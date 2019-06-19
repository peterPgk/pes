<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class TransformRequestByRole
{

    protected $forbiddenFields = [
        'in_probation',
        //This is decision if in our system each user can have only one role
        'role'
    ];

    /**
     * We limit this user to be able to edit only his profile, and only to certain fields
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (Auth::user()->hasRole($role)) {
            $user = $request->route('user');

            if (Auth::user()->isNot($user)) {
                throw UnauthorizedException::forRoles([$user->role]);
            }

            foreach ($this->forbiddenFields as $forbiddenField) {
                $request->offsetSet($forbiddenField, $user->{$forbiddenField});
            }
        }

        return $next($request);
    }
}
