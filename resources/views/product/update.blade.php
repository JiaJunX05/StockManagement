@extends("admin.layouts.app")

@section("title", "Update Product")
@section("content")

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="row g-0">

                    <div class="col-md-3 d-flex align-items-center justify-content-center p-3 bg-light">
                        <img src="{{ asset('assets/' . $product->feature) }}" alt="{{ $product->name }}" class="img-fluid" id="preview-image" style="max-width: 100%; max-height: 300px; object-fit: contain;">
                    </div>

                    <div class="col-md-5">
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
                            <h2 class="text-primary text-center mb-3">Update Product</h2>
                            <p class="text-muted text-center">Modify product details in the inventory.</p>
                            <hr>

                            <form action="{{ route('product.update.submit', $product->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="feature" class="form-label fw-bold">Product Features:</label>
                                    <input type="file" class="form-control" id="feature" name="feature">
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label fw-bold">Product Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Product Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ $product->description }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label fw-bold">Product Price:</label>
                                            <input type="text" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label fw-bold">Product Quantity:</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="{{ $product->quantity }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label fw-bold">Select Category :</label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option disabled value="">Select a Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ strtoupper($category->category_name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="zone_id" class="form-label fw-bold">Select Zone :</label>
                                            <select class="form-select" id="zone_id" name="zone_id" required>
                                                <option disabled>Select a zone</option>
                                                @foreach($zones as $zone)
                                                    <option value="{{ $zone->id }}" {{ $product->zone_id == $zone->id ? 'selected' : '' }}>
                                                        {{ strtoupper($zone->zone_name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="rack_id" class="form-label fw-bold">Select Rack :</label>
                                            <select class="form-select" id="rack_id" name="rack_id" data-selected="{{ $product->rack_id ?? '' }}">
                                                <option disabled>Select a rack</option>
                                                @foreach($storacks as $storack)
                                                    <option value="{{ $storack->rack->id }}" {{ $product->rack_id == $storack->rack->id ? 'selected' : '' }}>
                                                        {{ strtoupper($storack->rack->rack_number) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="sku_code" class="form-label fw-bold">Product SKU:</label>
                                    <input type="text" class="form-control text-uppercase" id="sku_code" name="sku_code" value="{{ $product->sku_code }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="barcode_number" class="form-label fw-bold">Barcode Number:</label>
                                    <input type="text" class="form-control" id="barcode_number" name="barcode_number" value="{{ $product->barcode ? $product->barcode->barcode_number : '' }}" required>
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
                        </div>
                    </div>

                    <div class="col-md-4">
                        @if($product->images->isNotEmpty())
                            <div class="row mt-3">
                                @foreach($product->images as $image)
                                    <div class="col-lg-6 col-md-12 mb-3">
                                        <div class="card shadow-sm border-0 position-relative">
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <input type="checkbox" name="remove_image[]" value="{{ $image->id }}"
                                                       id="remove_image_{{ $image->id }}" class="form-check-input">
                                            </div>

                                            <div class="card-body text-center p-3">
                                                <img src="{{ asset('assets/' . $image->image) }}" alt="Product Image"
                                                     class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                            </div>

                                            <div class="card-footer text-center bg-light border-0">
                                                <label for="remove_image_{{ $image->id }}" class="btn btn-danger btn-sm w-75">
                                                    Remove
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

                <div class="card-footer text-end p-3">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endsection

@section('scripts')
    <script>
        // 将 storacks 数据转换为 storacksData，确保 rack 存在
        window.storacksData = @json($storacks->map(function($storack) {
            return [
                'id' => $storack->rack->id ?? null,
                'rack_number' => $storack->rack->rack_number ?? '',
                'zone_id' => $storack->zone_id
            ];
        }));
    </script>

    <script src="{{ asset('assets/js/update.js') }}"></script>
    <script src="{{ asset('assets/js/w-update.js') }}"></script>
@endsection
