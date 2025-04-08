@extends("admin.layouts.app")

@section("title", "Create Product")
@section("content")

<link rel="stylesheet" href="{{ asset('assets/css/product-create.css') }}">
<div class="container py-4">
    <!-- 提示信息 -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 页面标题卡片 -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-plus-circle-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">Create Product</h4>
                        <p class="text-muted mb-0">Add a new product to the inventory system</p>
                    </div>
                </div>
                <a href="{{ route('product.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- 主要内容卡片 -->
    <div class="card shadow-sm border-0">
        <div class="row g-0">
            <!-- 左侧预览区域 -->
            <div class="col-md-5">
                <div class="preview-section d-flex flex-column h-100 bg-light p-4">
                    <!-- 主图预览 -->
                    <div class="main-preview bg-white rounded-3 p-3 mb-4" style="min-height: 400px;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="bi bi-image me-2"></i>Cover Image
                            </h6>
                            <span class="badge bg-white text-dark border px-3 py-2">800 x 600</span>
                        </div>
                        <div class="preview-container d-flex align-items-center justify-content-center" style="height: 350px;">
                            <i class="bi bi-image text-primary" id="preview-icon" style="font-size: 8rem;"></i>
                            <img src="" alt="Preview" id="preview-image" class="img-fluid rounded-3 d-none"
                                style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                        <div class="text-center mt-3">
                            <label for="cover_image" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-upload me-2"></i>Upload Cover Image
                            </label>
                            <input type="file" class="d-none" id="cover_image" name="cover_image" form="product-form">
                        </div>
                    </div>

                    <!-- 缩略图区域 -->
                    <div class="thumbnails-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="bi bi-images me-2"></i>Detail Images
                            </h6>
                            <label for="detail_image" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-lg me-2"></i>Add Images
                            </label>
                            <input type="file" class="d-none" id="detail_image" name="detail_image[]" multiple accept="image/*" form="product-form">
                        </div>

                        <!-- 新上传图片预览 -->
                        <div id="image-preview-container" class="row g-2"></div>
                    </div>
                </div>
            </div>

            <!-- 右侧表单区域 -->
            <div class="col-md-7">
                <div class="card-body p-4">
                    <h2 class="text-primary text-center mb-3">Create Product</h2>
                    <p class="text-muted text-center">Add a new product to the inventory system</p>
                    <hr>

                    <!-- 表单内容 -->
                    <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data" id="product-form">
                        @csrf

                        <div class="form-section">
                            <label for="name" class="form-label">Product Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-tag text-primary"></i></span>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" required>
                            </div>
                        </div>

                        <div class="form-section">
                            <label for="description" class="form-label">Product Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-pencil text-primary"></i></span>
                                <textarea class="form-control" id="description" name="description" placeholder="Enter Product Description" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-section">
                                <label for="price" class="form-label">Product Price</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-currency-dollar text-primary"></i></span>
                                    <input type="text" class="form-control" id="price" name="price" placeholder="Enter Product Price" required>
                                </div>
                            </div>

                            <div class="col-md-6 form-section">
                                <label for="quantity" class="form-label">Product Quantity</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-box text-primary"></i></span>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" placeholder="Enter Product Quantity" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-section">
                                <label for="category_id" class="form-label">Select Category</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-list text-primary"></i></span>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option selected disabled value="">Select a Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ strtoupper($category->category_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 form-section">
                                <label for="mapping_id" class="form-label">Select SubCategory</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-list text-primary"></i></span>
                                    <select class="form-select" id="mapping_id" name="subcategory_id" required>
                                        <option selected disabled value="">Select a SubCategory</option>
                                        @foreach($mappings as $mapping)
                                            <option value="{{ $mapping->id }}">{{ strtoupper($mapping->subcategory_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-section">
                                <label for="zone_id" class="form-label">Select Zone</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-diagram-3 text-primary"></i></span>
                                    <select class="form-select" id="zone_id" name="zone_id" required>
                                        <option selected disabled value="">Select a zone</option>
                                        @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}">{{ strtoupper($zone->zone_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 form-section">
                                <label for="location_id" class="form-label">Select Rack</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-primary"></i></span>
                                    <select class="form-select" id="location_id" name="rack_id" required>
                                        <option selected disabled value="">Select a rack</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}">{{ strtoupper($location->location_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-section">
                                <label for="brand_id" class="form-label">Select Brand</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-tag text-primary"></i></span>
                                    <select class="form-select" id="brand_id" name="brand_id" required>
                                        <option selected disabled value="">Select a Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ strtoupper($brand->brand_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 form-section">
                                <label for="color_id" class="form-label">Select Color</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-paint-bucket text-primary"></i></span>
                                    <select class="form-select" id="color_id" name="color_id" required>
                                        <option selected disabled value="">Select a Color</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}">{{ strtoupper($color->color_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <label for="sku_code" class="form-label">Product SKU</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-code-slash text-primary"></i></span>
                                <input type="text" class="form-control" id="sku_code" name="sku_code" placeholder="Enter Product SKU" required>
                            </div>
                        </div>

                        <div class="form-section">
                            <label for="barcode_number" class="form-label">Barcode Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-upc-scan text-primary"></i></span>
                                <input type="text" class="form-control" id="barcode_number" name="barcode_number" placeholder="Enter Barcode Number" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow-sm mt-4">
                            <i class="bi bi-check-circle me-2"></i>Create Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        // 将 locationsData 定义在全局作用域
        window.locationsData = @json($locations);
        window.mappingsData = @json($mappings);
    </script>

    <script src="{{ asset('assets/js/product-create.js') }}"></script>
@endsection
