@extends('layouts.app')

@section('title', __('dashboard.Users Management'))

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">{{ __('dashboard.Users Management') }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin_users_create') }}" class="btn btn-success">{{ __('dashboard.Add New') }}</a>
                <button id="export-users" class="btn btn-outline-primary">{{ __('dashboard.Export CSV') }}</button>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" id="filter-country" class="form-control" placeholder="{{ __('dashboard.Country') }}">
            </div>
            <div class="col-md-3">
                <input type="text" id="filter-city" class="form-control" placeholder="{{ __('dashboard.City') }}">
            </div>
            <div class="col-md-3">
                <button id="apply-filters" class="btn btn-primary w-100">{{ __('dashboard.Filter') }}</button>
            </div>
            <div class="col-md-3">
                <button id="reset-filters" class="btn btn-secondary w-100">{{ __('dashboard.Reset') }}</button>
            </div>
        </div>

        <table id="users-table" class="table table-bordered table-hover w-100">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>{{ __('dashboard.First Name') }}</th>
                    <th>{{ __('dashboard.Last Name') }}</th>
                    <th>{{ __('dashboard.Email') }}</th>
                    <th>{{ __('dashboard.Phone') }}</th>
                    <th>{{ __('dashboard.Country') }}</th>
                    <th>{{ __('dashboard.Verified') }}</th>
                    <th>{{ __('dashboard.Actions') }}</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        window.translations = {
            edit: '{{ __("dashboard.Edit") }}',
            delete: '{{ __("dashboard.Delete") }}',
            sendEmail: '{{ __("dashboard.Send Email") }}',
            confirmDelete: '{{ __("dashboard.Are you sure you want to delete this user?") }}',
            deleteSuccess: '{{ __("dashboard.User deleted successfully") }}',
            error: '{{ __("dashboard.Error occurred") }}',
            yes: '{{ __("Yes") }}',
            no: '{{ __("No") }}'
        };
    </script>

    @vite('resources/js/users.js')
@endpush