@extends('layouts.app')

@section('title', __('dashboard.Dashboard'))

@section('content')
    <div class="container py-5">
        <h1 class="fw-bold mb-4">{{ __('dashboard.Dashboard') }}</h1>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h5>{{ __('dashboard.Verified Users') }}</h5>
                        <h2>{{ $data['verified_users'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h5>{{ __('dashboard.Total Products') }}</h5>
                        <h2>{{ $data['total_products'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h5>{{ __('dashboard.New Users (Last Month)') }}</h5>
                        <h2>{{ $data['new_users_last_month'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4 shadow-sm">
            <div class="card-body">
                <h5>{{ __('dashboard.Products Added (Last 7 Days)') }}</h5>
                <canvas id="productsChart" height="120"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const labels = @json(array_keys($data['products_last_week'] ?? []));
            const values = @json(array_values($data['products_last_week'] ?? []));

            const ctx = document.getElementById('productsChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: '{{ __("dashboard.Products") }}',
                        data: values,
                        borderWidth: 1,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
@endpush