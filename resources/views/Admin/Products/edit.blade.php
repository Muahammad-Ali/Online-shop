@extends('Admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Edit Product</h1>
</section>

<section class="content">
    @include('Admin.message')
    <div class="container-fluid">
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Read-only fields -->
            
            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" id="sku" name="sku" class="form-control" value="{{ $product->sku }}" readonly>
            </div>

            <!-- Editable fields -->
            <div class="form-group">
                <label for="title">Product Title</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ $product->title }}" required>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" class="form-control" value="{{ $product->price }}" required>
            </div>

            <div class="form-group">
                <label for="qty">Quantity</label>
                <input type="number" id="qty" name="qty" class="form-control" value="{{ $product->qty }}" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection
