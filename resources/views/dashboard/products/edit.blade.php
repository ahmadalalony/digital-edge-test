@extends('layouts.app')

@section('title', __('dashboard.Products'))

@section('content')
    <div class="container mt-4">
        <h3 class="mb-3">{{ __('dashboard.Edit Product') }} #{{ $product->id }}</h3>

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

        <form method="POST" action="{{ route('admin_products_update', $product->id) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('dashboard.Title EN') }}</label>
                    <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                        value="{{ old('title_en', $product->title_en) }}" required>
                    @error('title_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('dashboard.Title AR') }}</label>
                    <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror"
                        value="{{ old('title_ar', $product->title_ar) }}" required>
                    @error('title_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('dashboard.Price') }}</label>
                    <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', $product->price) }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('dashboard.Primary Image URL') }}</label>
                    <input type="text" name="primary_image"
                        class="form-control @error('primary_image') is-invalid @enderror"
                        value="{{ old('primary_image', $product->primary_image) }}">
                    @error('primary_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('dashboard.Description EN') }}</label>
                    <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror"
                        rows="3">{{ old('description_en', $product->description_en) }}</textarea>
                    @error('description_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('dashboard.Description AR') }}</label>
                    <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror"
                        rows="3">{{ old('description_ar', $product->description_ar) }}</textarea>
                    @error('description_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-success">{{ __('dashboard.Save Changes') }}</button>
                <button type="button" class="btn btn-danger" id="delete-product">{{ __('dashboard.Delete') }}</button>
                <a href="{{ route('admin_products_index') }}" class="btn btn-secondary">{{ __('dashboard.Cancel') }}</a>
            </div>
        </form>

        @if($product->assignedUsers->count() > 0)
            <div class="mt-5">
                <h4>{{ __('dashboard.Assigned Users') }}</h4>
                <table class="table table-bordered mt-2">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard.Name') }}</th>
                            <th>{{ __('dashboard.Email') }}</th>
                            <th>{{ __('dashboard.Country') }}</th>
                            <th>{{ __('dashboard.City') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->assignedUsers as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->country }}</td>
                                <td>{{ $user->city }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.getElementById('delete-product')?.addEventListener('click', function () {
                if (confirm('{{ __('dashboard.Are you sure you want to delete this product?') }}')) {
                    fetch('{{ route('admin_products_destroy', $product->id) }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json()).then(() => {
                        window.location.href = '{{ route('admin_products_index') }}';
                    });
                }
            });
        </script>
    @endpush
@endsection