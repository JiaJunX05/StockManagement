@extends("admin.layouts.app")

@section("title", "Create Category")
@section("content")

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="container text-center">
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
                    </div>

                    <!-- Form Title -->
                    <h2 class="text-primary text-center mb-3">Create Category</h2>
                    <p class="text-muted text-center">Add a new category to organize and manage products efficiently.</p><hr>

                    <!-- Form -->
                    <form action="{{ route('category.create.submit') }}" method="post">
                        @csrf

                        <div class="mb-3">
                            <label for="category_name" class="form-label fw-bold">Category Name :</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter Category Name" autofocus required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow-sm mt-3">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
