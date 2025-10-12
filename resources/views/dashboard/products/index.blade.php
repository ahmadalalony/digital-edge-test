@extends('layouts.app')

@section('title', __('dashboard.Products'))

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">{{ __('dashboard.Products Management') }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin_products_create') }}" class="btn btn-success">{{ __('dashboard.Add New') }}</a>
                <button id="export-products" class="btn btn-outline-primary">{{ __('dashboard.Export CSV') }}</button>
            </div>
        </div>

        <table id="products-table" class="table table-bordered table-hover w-100">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>{{ __('dashboard.Title EN') }}</th>
                    <th>{{ __('dashboard.Title AR') }}</th>
                    <th>{{ __('dashboard.Price') }}</th>
                    <th>{{ __('dashboard.Created By') }}</th>
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
            confirmDelete: '{{ __("dashboard.Are you sure you want to delete this product?") }}',
            deleteSuccess: '{{ __("dashboard.Product deleted successfully") }}',
            error: '{{ __("dashboard.Error occurred") }}'
        };
    </script>

    @vite('resources/js/products.js')
@endpush