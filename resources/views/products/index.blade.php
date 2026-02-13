@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Products</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(auth()->user()->is_admin)
        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price (USD)</th>
                <th>Price (EUR)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price_usd, 2) }}</td>
                    <td>{{ number_format($product->price_eur, 2) }}</td>
                    @if(auth()->user()->is_admin)
                        <td>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">{{__('No products found')}}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
