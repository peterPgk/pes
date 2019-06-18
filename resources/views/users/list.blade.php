@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center mb-2">

            <div class="col-md-8">
                <a class="btn btn-primary float-right" href="{{ route('users.create') }}">New user</a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <ul class="list-group">
                    @foreach($users as $user)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-9">
                                    <div>
                                        {{ $user->name }} |
                                        {{ $user->email }} |
                                        {{ $user->role }}
                                    </div>
                                    <div>
                                        {{ $user->address }}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <a class="btn btn-primary float-right" href="{{ route('users.edit', ['user' => $user]) }}">Edit</a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

@endsection