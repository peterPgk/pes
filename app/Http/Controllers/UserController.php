<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$users = User::all();

    	return view('users.list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $data = $request->validate(User::rules());

        User::addWithRole($data);

        return redirect()->route('users.index');


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(Request $request, User $user)
    {
        $new_rules = [
            'email' => 'required|string|email|max:255|unique:users,email,'. $user->id. ',id',
            'employee_id' => 'nullable|unique:users,employee_id,'. $user->id. ',id',
            'password' => 'nullable|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ];

        //Need to change some rules, because we make update, not insert
        $rules = array_merge(User::rules(), $new_rules);

        //Validator will filter all provided data, and return only validated one
        $data = $request->validate($rules);

        //Handle checkbox
        $data['in_probation'] = $request->has('in_probation') && $request->get('in_probation');

        $user =  \DB::transaction(function () use ($user, $data) {
            $user->update($data);
            $user->syncRoles($data['role']);

            return $user;
        });

	    flash('The '. $user->name . ' was edited successfully')->success();

		return auth()->user()->hasRole('manager')
			? redirect()->route('users.index')
			: redirect()->route('users.edit', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            flash('The '. $user->name . ' was removed successfully')->success();
        }
        catch(\Exception $e) {
            flash('There was an error trying to remove '. $user->name)->error();
            \Log::error($e->getMessage());
        }

        return redirect()->route('users.index');
    }
}
