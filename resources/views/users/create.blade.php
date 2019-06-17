@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <ul class="list-group">
                    {{ $user->name }}
                </ul>
            </div>
        </div>
    </div>

@endsection