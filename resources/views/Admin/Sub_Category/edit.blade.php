@extends('Admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Sub-Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('Sub-Categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('Sub-Categories.update', $subCategory->id) }}" method="POST" id="subCategoryEditForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $subCategory->name }}" required>
                        </div>

                        <!-- Slug Field (Readonly) -->
                        <div class="col-md-6 mb-3">
                            <label for="slug">Slug</label>
                            <input type="text"  name="slug" id="slug" class="form-control" value="{{ $subCategory->slug }}" required>
                        </div>

                        <!-- Category Field -->
                        <div class="col-md-6 mb-3">
                            <label for="category">Category</label>
                            <select name="category" class="form-control" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $subCategory->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Field -->
                        <div class="col-md-6 mb-3">
                            <label for="status">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="1" {{ $subCategory->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $subCategory->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">show on Home</label>
                                <select name="showHome" class="form-control" id="showHome">
                                    <option value="Yes" {{ $subCategory->showHome == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ $subCategory->showHome == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('Sub-Categories.index') }}" class="btn btn-outline-secondary ml-3">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Auto-generate slug from name
        $("#name").on('change', function() {
            let element = $(this);
            $.ajax({
                url: '{{ route("getSlug") }}',
                type: 'get',
                data: { title: element.val() },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $("#slug").val(response.slug); // Update slug field
                    }
                },
                error: function() {
                    console.error("Failed to generate slug.");
                }
            });
        });
    });
</script>
@endsection
