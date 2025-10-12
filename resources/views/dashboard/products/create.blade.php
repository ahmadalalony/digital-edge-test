@extends('layouts.app')

@section('title', __('dashboard.Products'))

@section('content')
    <div class="container mt-4">
        <h3 class="mb-3">{{ __('dashboard.Add Product') }}</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin_products_store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('dashboard.Title EN') }}</label>
                    <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                        value="{{ old('title_en') }}" required>
                    @error('title_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('dashboard.Title AR') }}</label>
                    <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror"
                        value="{{ old('title_ar') }}" required>
                    @error('title_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('dashboard.Price') }}</label>
                    <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price') }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('dashboard.Primary Image URL') }}</label>
                    <input type="text" name="primary_image"
                        class="form-control @error('primary_image') is-invalid @enderror"
                        value="{{ old('primary_image') }}">
                    @error('primary_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('dashboard.Description EN') }}</label>
                    <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror"
                        rows="3">{{ old('description_en') }}</textarea>
                    @error('description_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('dashboard.Description AR') }}</label>
                    <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror"
                        rows="3">{{ old('description_ar') }}</textarea>
                    @error('description_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-success">{{ __('dashboard.Save') }}</button>
                <a href="{{ route('admin_products_index') }}" class="btn btn-secondary">{{ __('dashboard.Cancel') }}</a>
            </div>
        </form>
    </div>
@endsection