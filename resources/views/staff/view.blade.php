@extends("staff.layouts.app")

@section("title", "View Product")
@section("content")

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="row g-0">

                    <div class="col-md-5 d-flex align-items-center justify-content-center p-3 bg-light">
                        <img src="{{ asset('assets/' . $product->feature) }}"
                            alt="{{ $product->name }}" class="img-fluid" id="preview-image" style="max-width: 100%; max-height: 300px; object-fit: contain;">
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
                            <h2 class="text-primary text-center mb-3">View Product</h2>
                            <p class="text-muted text-center">View and manage your product here.</p><hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-bold">Product Name:</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_name" class="form-label fw-bold">Product Category:</label>
                                        <input type="text" class="form-control" id="category_name" name="category_name"
                                            value="{{ strtoupper($product->category->category_name ?? 'No Category') }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Product Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="1" readonly>{{ $product->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label fw-bold">Product Price:</label>
                                <input type="text" class="form-control" id="price" name="price" value="RM {{ $product->price }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label fw-bold">Product Quantity:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="quantity" name="quantity" min="1" value="{{ $product->quantity }} Units" readonly>
                                    <a href="{{ route('stock', $product->id) }}" class="btn btn-success w-25">Stock</a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="zone_id" class="form-label fw-bold">Select Zone :</label>
                                        <input type="text" class="form-control" id="zone_id" name="zone_id"
                                               value="{{ strtoupper(optional($product->zone)->zone_name ?? 'No Zone') }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="rack_id" class="form-label fw-bold">Select Rack :</label>
                                        <input type="text" class="form-control" id="rack_id" name="rack_id"
                                               value="{{ strtoupper(optional($product->rack)->rack_number ?? 'No Rack') }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="sku_code" class="form-label fw-bold">Product SKU:</label>
                                <input type="text" class="form-control text-uppercase" id="sku_code" name="sku_code" value="{{ $product->sku_code }}" readonly>
                            </div>

                            <div class="mb-5">
                                <label for="barcode-number" class="form-label fw-bold me-2">Barcode Number:</label>
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ $product->barcode ? asset('assets/' . $product->barcode->barcode_image) : '' }}"
                                         alt="{{ $product->sku_code }}" class="img-fluid" style="max-width: 200px;">
                                    <span class="fw-bold fs-5 mt-2">{{ $product->barcode ? $product->barcode->barcode_number : 'No Barcode' }}</span>
                                </div>
                            </div>

                            @if ($product->images)
                                <div class="row mt-3 d-flex flex-wrap justify-content-center">
                                    @foreach ($product->images as $image)
                                        <div class="col-sm-12 col-md-6 col-lg-2 m-2 d-flex justify-content-center align-items-center">
                                            <div class="position-relative">
                                                <img src="{{ asset('assets/' . $image->image) }}" alt="Image" class="img-fluid" style="max-width: 100%; max-height: 300px; object-fit: contain;">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-3 d-flex justify-content-between">
                                <a href="{{ route('update', $product->id) }}" class="btn btn-warning w-100 me-2">Edit</a>

                                {{-- <form action="{{ route('destroy', $product->id)}}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" class="w-50">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger w-100">Delete</button>
                                </form> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
