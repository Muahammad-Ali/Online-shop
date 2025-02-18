@extends('Admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Edit Product</h1>
</section>

<section class="content">
    @include('Admin.message')
    <div class="container-fluid">
        <form action="{{ route('products.update', $product->id) }}" method="POST" id="productForm">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control" value="{{ $product->title }}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" readonly name="slug" id="slug" class="form-control" value="{{ $product->slug }}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="summernote">{{ $product->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Include other sections like Pricing, Inventory as in create form -->
                </div>
                <div class="col-md-4">
                    <!-- Include Status, Category, Brand, Featured Product as in create form -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product Status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Blocked</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product Category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select a Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <p class="error"></p>
                            </div>
                            <!-- Subcategory dropdown -->
                        </div>
                    </div>
                    <!-- Include other sections like Brand, Featured -->
                </div>
            </div>
            <!-- Add other necessary fields and buttons -->
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection
