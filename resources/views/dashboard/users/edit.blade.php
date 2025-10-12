@extends('layouts.app')

@section('title', __('users.edit_user'))

@section('content')
    <div class="container">
        <h3>{{ __('users.edit_user') }}</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin_users_update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('users.first_name') }}</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                        class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('users.last_name') }}</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                        class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('users.email') }}</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('users.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('users.country') }}</label>
                    <input type="text" name="country" value="{{ old('country', $user->country) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('users.city') }}</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" class="form-control">
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">{{ __('dashboard.Save Changes') }}</button>
                <a href="{{ route('admin_users_index') }}" class="btn btn-secondary">{{ __('dashboard.Cancel') }}</a>
            </div>
        </form>

        <div class="mt-5">
            <h4>{{ __('dashboard.Assigned Products') }}</h4>
            <table class="table table-bordered mt-2">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('dashboard.Title EN') }}</th>
                        <th>{{ __('dashboard.Title AR') }}</th>
                        <th>{{ __('dashboard.Price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->assignedProducts as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->title_en }}</td>
                            <td>{{ $product->title_ar }}</td>
                            <td>{{ $product->price }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">{{ __('dashboard.No data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <hr class="my-4">
        <h4>{{ __('dashboard.Admin Change Password') }}</h4>
        <p class="text-muted">
            {{ __('dashboard.As an admin, you can change user password without knowing the current password') }}
        </p>

        <form method="POST" action="{{ route('admin_users_admin_change_password', $user->id) }}" class="mt-3">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('dashboard.New Password') }}</label>
                    <input type="password" name="new_password"
                        class="form-control @error('new_password') is-invalid @enderror" required>
                    @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('dashboard.Confirm New Password') }}</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-key"></i> {{ __('dashboard.Change Password') }}
                </button>
            </div>
        </form>
    </div>
@endsection