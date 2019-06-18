@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('Edit user') }}
                        @role('manager')
                        <a class="btn btn-primary float-right" href="{{ route('users.index') }}">Back</a>
                        @endrole
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', ['user' => $user]) }}" novalidate>
                            @method('PUT')
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name"
                                           type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           required
                                           autocomplete="name"
                                           autofocus
                                    >

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                                <div class="col-md-6">
                                    <input id="address"
                                           type="text"
                                           class="form-control @error('address') is-invalid @enderror"
                                           name="address"
                                           value="{{ old('address', $user->address) }}"
                                           required
                                           autocomplete="address"
                                           autofocus
                                    >

                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                                <div class="col-md-6">
                                    <input id="phone"
                                           type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           name="phone"
                                           value="{{ old('phone', $user->phone) }}"
                                           required
                                           autocomplete="phone"
                                           autofocus
                                    >

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email"
                                           type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           required
                                           autocomplete="email"
                                    >

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="employee_id" class="col-md-4 col-form-label text-md-right">{{ __('Employee ID') }}</label>

                                <div class="col-md-6">
                                    <input id="employee_id"
                                           type="text"
                                           class="form-control @error('employee_id') is-invalid @enderror"
                                           name="employee_id"
                                           value="{{ old('employee_id', $user->employee_id) }}"
                                           required
                                           autocomplete="employee_id"
                                    >

                                    @error('employee_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password"
                                           type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password"
                                           required
                                           autocomplete="new-password"
                                    >

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="date_of_birth" class="col-md-4 col-form-label text-md-right">{{ __('Date of Birth') }}</label>

                                <div class="col-md-6">
                                    <input id="date_of_birth"
                                           type="date"
                                           class="form-control @error('date_of_birth') is-invalid @enderror"
                                           name="date_of_birth"
                                           value="{{ old('date_of_birth', $user->date_of_birth) }}"
                                           required
                                           autocomplete="date_of_birth"
                                    >

                                    @error('date_of_birth')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control @error('role') is-invalid @enderror"
                                            id="role"
                                            name="role"
                                            @unlessrole('manager') disabled @endunlessrole
                                    >
                                        @foreach ($roles as $role)
                                            <option @if($user->role === $role->name) selected="selected" @endif
                                            value="{{ mb_strtolower($role->name, 'UTF-8') }}">{{ ucwords(__($role->name)) }}</option>
                                        @endforeach
                                    </select>

                                    @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input @error('in_probation') is-invalid @enderror"
                                               type="checkbox"
                                               name="in_probation"
                                               id="in_probation" {{ $user->in_probation ? 'checked' : '' }}
                                               @unlessrole('manager') disabled @endunlessrole
                                        >

                                        <label class="form-check-label" for="in_probation">
                                            {{ __('In Probation') }}
                                        </label>
                                        @error('in_probation')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Edit') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        @role('manager')
                        <form method="POST" action="{{ route('users.destroy', ['user' => $user]) }}" novalidate>
                            @method('DELETE')
                            @csrf
                            <div class="form-group row mb-0 mt-1">
                                <div class="col-md-6 offset-md-4">
                                    <button class="btn btn-primary">{{ __('Delete') }}</button>
                                </div>
                            </div>
                        </form>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection