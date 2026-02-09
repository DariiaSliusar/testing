@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Product</h1>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="price_usd" class="form-label">Price (USD)</label>
            <input type="number" name="price_usd" id="price_usd" class="form-control" value="{{ old('price_usd') }}" step="0.01" min="0" required>
            @error('price_usd')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="price_eur" class="form-label">Price (EUR)</label>
            <input type="number" name="price_eur" id="price_eur" class="form-control" value="{{ old('price_eur') }}" step="0.01" min="0" required>
            @error('price_eur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
