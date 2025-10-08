@extends('layouts.app')

@section('title', __('Edit User'))

@section('content')
    <div class="container">
        <h3>{{ __('Edit User') }}</h3>
        <form id="edit-user-form">
            @csrf
            <div class="mb-3">
                <label>{{ __('First Name') }}</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>{{ __('Last Name') }}</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>{{ __('Email') }}</label>
                <input type="email" name="email" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">{{ __('Save Changes') }}</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const id = "{{ $id }}";
            const token = localStorage.getItem('auth_token');

            fetch(`/api/users/${id}`, {
                headers: { 'Authorization': 'Bearer ' + token }
            })
                .then(res => res.json())
                .then(data => {
                    const user = data.data;
                    document.querySelector('[name=first_name]').value = user.first_name;
                    document.querySelector('[name=last_name]').value = user.last_name;
                    document.querySelector('[name=email]').value = user.email || '';
                });

            document.getElementById('edit-user-form').addEventListener('submit', e => {
                e.preventDefault();

                fetch(`/api/users/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        first_name: document.querySelector('[name=first_name]').value,
                        last_name: document.querySelector('[name=last_name]').value,
                        email: document.querySelector('[name=email]').value,
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) alert('User updated successfully');
                        else alert('Error updating user');
                    });
            });
        });
    </script>
@endsection