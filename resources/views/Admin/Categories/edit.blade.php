@extends('Admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('Categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="" method="post" id="categoryEditForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}" placeholder="Name">
                            <p></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="slug">Slug</label>
                            <input type="text" readonly name="slug" id="slug" class="form-control" value="{{ $category->slug }}" placeholder="Slug">
                            <p></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="hidden" id="image_id" name="image_id" value="">
                            <label for="image">Image</label>
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">
                                    @if($category->image)
                                        <img src="{{ asset('uploads/category/' . $category->image) }}" alt="Current Image" style="max-width: 150px;">
                                    @endif
                                    <br>Drop files here or click to upload.<br><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status">Status</label>
                            <select name="status" class="form-control" id="status">
                                <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">show on Home</label>
                                <select name="showHome" class="form-control" id="showHome">
                                    <option value="Yes" {{ $category->showHome == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No"{{ $category->showHome == 'No'? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('Categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
    <script>
        $("#categoryEditForm").submit(function(event) {
            event.preventDefault();
            var form = $(this);

            $("button[type=submit]").prop("disabled", true);
            $.ajax({
                url: '{{ route("Categories.update", $category->id) }}',
                type: 'post',
                data: form.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop("disabled", false);
                    if (response.status) {
                        window.location.href = "{{ route('Categories.index') }}";
                    } else {
                        var errors = response.errors;
                        if (errors.name) {
                            $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.name);
                        } else {
                            $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                        }
                        if (errors.slug) {
                            $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.slug);
                        } else {
                            $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                        }
                    }
                },
                error: function() {
                    console.log("Something went wrong");
                }
            });
        });

        $("#name").change(function() {
            let element = $(this);
            $.ajax({
                url: '{{ route("getSlug") }}',
                type: 'get',
                data: { title: element.val() },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $("#slug").val(response.slug);
                    }
                }
            });
        });

        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            url: "{{ route('temp-images-create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(file, response) {
                $("#image_id").val(response.image_id);
            }
        });
    </script>
@endsection
