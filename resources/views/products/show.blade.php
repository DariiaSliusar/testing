@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Product Details</h1>
    <div class="mb-3">
        <strong>Name:</strong> {{ $product->name }}
    </div>
    <div class="mb-3">
        <strong>Price:</strong> {{ $product->price }}
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
</div>
@endsection
