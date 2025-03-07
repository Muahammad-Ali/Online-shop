@extends('Admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Edit Product</h1>
</section>

<section class="content">
    @include('Admin.message')
    <div class="container-fluid">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')

            <!-- Product Title -->
            <div class="mb-3">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $product->title) }}">
            </div>

            <!-- Slug (Read-Only) -->
            <div class="mb-3">
                <label for="slug">Slug</label>
                <input type="text" readonly name="slug" id="slug" class="form-control" value="{{ old('slug', $product->slug) }}">
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="summernote">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="short_description">short_description</label>
                <textarea name="short_description" id="short_description" class="summernote">{{ old('short_description', $product->short_description) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="shipping_returns">shipping_returns</label>
                <textarea name="shipping_returns" id="shipping_returns" class="summernote">{{ old('shipping_returns', $product->shipping_returns) }}</textarea>
            </div>

            <!-- Price -->
            <div class="mb-3">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}">
            </div>

            <!-- Compare Price -->
            <div class="mb-3">
                <label for="compare_price">Compare Price</label>
                <input type="text" name="compare_price" id="compare_price" class="form-control" value="{{ old('compare_price', $product->compare_price) }}">
            </div>

            <!-- SKU -->
            <div class="mb-3">
                <label for="sku">SKU</label>
                <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
            </div>

            <!-- Barcode -->
            <div class="mb-3">
                <label for="barcode">Barcode</label>
                <input type="text" name="barcode" id="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}">
            </div>

            <!-- Track Quantity -->
            <div class="mb-3">
                <label>Track Quantity</label>
                <input type="hidden" name="track_qty" value="No">
                <input type="checkbox" name="track_qty" value="Yes" {{ old('track_qty', $product->track_qty) == "Yes" ? 'checked' : '' }}>
            </div>

            <!-- Quantity -->
            <div class="mb-3">
                <label for="qty">Quantity</label>
                <input type="number" min="0" name="qty" id="qty" class="form-control" value="{{ old('qty', $product->qty) }}">
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label for="category">Category</label>
                <select name="category" id="category" class="form-control">
                    <option value="">Select a Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subcategory -->
            {{-- <div class="mb-3">
                <label for="sub_category">Subcategory</label>
                <select name="sub_category" id="sub_category" class="form-control">
                    <option value="">Select a Subcategory</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" {{ old('sub_category', $product->sub_category_id) == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                    @endforeach
                </select>
            </div> --}}

            <!-- Brand -->
            <div class="mb-3">
                <label for="brand">Brand</label>
                <select name="brand" id="brand" class="form-control">
                    <option value="">Select a Brand</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Is Featured -->
            <div class="mb-3">
                <label for="is_featured">Featured Product</label>
                <select name="is_featured" id="is_featured" class="form-control">
                    <option value="No" {{ old('is_featured', $product->is_featured) == "No" ? 'selected' : '' }}>No</option>
                    <option value="Yes" {{ old('is_featured', $product->is_featured) == "Yes" ? 'selected' : '' }}>Yes</option>
                </select>
            </div>


            <div class=" card mb-3">
                <div class="card-body">
                    <h2 class="h4 mb-3">Related Products</h2>
                    <div class="mb-3">
                        <select class="related-products w-100" name="related_products" id="related_products"></select>
                        <p class="error"></p>
                    </div>
                </div>

            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>

    </div>
</section>
@endsection


@section('customJs')

<script>
$(document).ready(function() {
    $('.related-products').select2({
        ajax: {
            url: '{{ route("products.getProducts") }}',
            dataType: 'json',
            delay: 250,  // Add delay to reduce server load
            data: function (params) {
                return {
                    term: params.term  // Send search term
                };
            },
            processResults: function (data) {
                return {
                    results: data.tags  // Ensure correct property
                };
            }
        },
        minimumInputLength: 2,  // Adjust minimum input length
        multiple: true
    });
});


.select2-container--default .select2-selection--multiple .select2-selection__rendered li{
  color:#000;
}


// $array = [ 'tags' => [
//          [
//             "id" => 1,
//             "text" => 'a'
//         ] ,
//         [
//             "id" => 2,
//             "text" => 'b'
//         ],
//         [
//             "id" => 3,
//             "text" => 'c'
//         ]
//     ]
// ];
</script>
