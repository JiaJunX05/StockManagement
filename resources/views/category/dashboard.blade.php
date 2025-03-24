@extends("admin.layouts.app")

@section("title", "Admin Panel")
@section("content")

<div class="container text-center mt-5">
    <!-- Success Alert -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Error Alert -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <h3 class="text-md-start mb-0">Category List Management</h3>

        <form class="d-flex gap-3 align-items-center" role="search" id="search-form">
            <select class="form-select" id="category_id" name="category_id" style="width: 200px;">
                <option selected value="">Select a Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ strtoupper($category->category_name) }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <hr>

    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('assets/icons/Category.png') }}" alt="Category Icon" class="img-fluid" style="width: 30px;">
                            <h4 class="card-title text-primary mb-0">{{ $category->category_name }}</h4>
                        </div>
                    </div>

                    <div class="card-footer text-body-secondary">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('category.update', $category->id) }}" class="btn btn-warning btn-sm" style="width: 100px;">Edit</a>
                            <form action="{{ route('category.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm" style="width: 100px;">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endsection
