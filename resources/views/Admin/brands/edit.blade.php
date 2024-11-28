@extends('Admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>Edit Brand</h1>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('brands.update', $brand->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $brand->name }}" required>
                </div>

                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" class="form-control" name="slug" id="slug" value="{{ $brand->slug }}" required>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" name="status" id="status">
                        <option value="1" {{ $brand->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $brand->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Brand</button>
            </form>
        </div>
    </section>
@endsection
