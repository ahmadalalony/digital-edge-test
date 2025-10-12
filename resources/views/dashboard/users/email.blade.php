@extends('layouts.app')

@section('title', __('users.send_email'))

@section('content')
    <div class="container">
        <h3>{{ __('users.send_email_to_user', ['name' => $user->first_name . ' ' . $user->last_name]) }}</h3>

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

        <form method="POST" action="{{ route('admin_users_send_email', $user->id) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ __('users.subject') }}</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ __('users.message') }}</label>
                <textarea name="message" rows="6" class="form-control" required>{{ old('message') }}</textarea>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">{{ __('users.send_email') }}</button>
                <a href="{{ route('admin_users_index') }}" class="btn btn-secondary">{{ __('dashboard.Cancel') }}</a>
            </div>
        </form>
    </div>
@endsection