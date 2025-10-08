@extends('layouts.app')

@section('title', __('Users Management'))

@section('content')
    <div class="container-fluid mt-4">
        <h2 class="mb-4">{{ __('Users Management') }}</h2>

        <table id="users-table" class="table table-bordered table-hover w-100">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>{{ __('First Name') }}</th>
                    <th>{{ __('Last Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Phone') }}</th>
                    <th>{{ __('Country') }}</th>
                    <th>{{ __('Verified') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/users.js') }}"></script>
@endpush