@extends("admin.layouts.app")

@section("title", "Create Product")
@section("content")

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="row g-0">

                    <div class="col-md-5 d-flex align-items-center justify-content-center p-3 bg-light">
                        <img src="{{ asset('assets/icons/Logo.png') }}"
                            alt="Preview" class="img-fluid" id="preview-image" style="max-width: 100%; max-height: 300px; object-fit: contain;">
                    </div>

                    <div class="col-md-7">
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
                            <h2 class="text-primary text-center mb-3">Create Product</h2>
                            <p class="text-muted text-center">Add a new product to the inventory system.</p><hr>

                            <!-- Form -->
                            <form action="{{ route('product.create.submit') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="feature" class="form-label fw-bold">Product Features:</label>
                                    <input type="file" class="form-control" id="feature" name="feature" required>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label fw-bold">Product Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Product Name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Product Description:</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter Product Description" rows="3"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label fw-bold">Product Price:</label>
                                            <input type="text" class="form-control" id="price" name="price" placeholder="Enter Product Price" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label fw-bold">Product Quantity:</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" placeholder="Enter Product Quantity" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label fw-bold">Select Category :</label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option selected disabled value="">Select a Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ strtoupper($category->category_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="zone_id" class="form-label fw-bold">Select Zone :</label>
                                            <select class="form-select" id="zone_id" name="zone_id" required>
                                                <option selected disabled value="">Select a zone</option>
                                                @foreach($zones as $zone)
                                                    <option value="{{ $zone->id }}">{{ strtoupper($zone->zone_name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="rack_id" class="form-label fw-bold">Select Rack :</label>
                                            <select class="form-select" id="rack_id" name="rack_id" disabled>
                                                <option selected disabled value="">Select a rack</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="sku_code" class="form-label fw-bold">Product SKU:</label>
                                    <input type="text" class="form-control text-uppercase" id="sku_code" name="sku_code" placeholder="Enter Product SKU" required>
                                </div>

                                <div class="mb-3">
                                    <label for="barcode_number" class="form-label fw-bold">Barcode Number:</label>
                                    <input type="text" class="form-control" id="barcode_number" name="barcode_number" placeholder="Enter Barcode Number" required>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label fw-bold">Product Images:</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="image" name="image[]" multiple accept="image/*">
                                        <label class="input-group-text" for="image">Upload</label>
                                    </div>
                                    <small class="text-muted">You can upload up to 10 images.</small>
                                </div>
                                <div id="image-preview-container" class="row g-2 mt-3"></div>

                                <button type="submit" class="btn btn-primary w-100 shadow-sm mt-3">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        // 将 storacksData 定义在全局作用域
        window.storacksData = @json($storacks);
    </script>

    <script src="{{ asset('assets/js/create.js') }}"></script>
    <script src="{{ asset('assets/js/w-create.js') }}"></script>
@endsection
