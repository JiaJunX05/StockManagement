@extends("admin.layouts.app")

@section("title", "View Product")
@section("content")

<link rel="stylesheet" href="{{ asset('assets/css/product-view.css') }}">
<div class="container py-4">
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
                        <i class="bi bi-eye-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">View Product</h4>
                        <p class="text-muted mb-0">View product details</p>
                    </div>
                </div>
                <a href="{{ route('product.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="row g-0">
            <!-- 左侧图片预览 -->
            <div class="col-md-5 p-4 bg-light">
                <div class="text-center">
                    <img src="{{ asset('assets/images/products/' . $product->cover_image) }}"
                         alt="{{ $product->name }}"
                         class="img-fluid rounded mb-3 product-main-image"
                         id="mainImage">

                    @if ($product->images)
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <img src="{{ asset('assets/images/products/' . $product->cover_image) }}"
                                 alt="Main"
                                 class="rounded border border-2 border-primary p-1 product-thumbnail"
                                 onclick="changeImage(this.src)">
                            @foreach ($product->images as $image)
                                <img src="{{ asset('assets/images/products/' . $image->detail_image) }}"
                                     alt="Detail"
                                     class="rounded border p-1 product-thumbnail"
                                     onclick="changeImage(this.src)">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- 右侧产品信息 -->
            <div class="col-md-7">
                <div class="p-4">
                    <h1 class="h3 fw-bold mb-3">{{ $product->name }}</h1>

                    <div class="d-flex align-items-center gap-3 mb-4 text-muted">
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                        <span>4.8</span>
                        <span>|</span>
                        <span>1.4k Ratings</span>
                    </div>

                    <div class="bg-light p-3 rounded mb-4">
                        <div class="text-decoration-line-through text-muted">
                            RM {{ number_format($product->price * 1.5, 2) }}
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="h2 text-danger mb-0">RM {{ number_format($product->price, 2) }}</div>
                            <span class="badge bg-danger">-33%</span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- 基本信息 -->
                        <div class="col-12">
                            <div class="d-flex border-bottom py-2">
                                <div class="text-muted label-width">SKU</div>
                                <div class="fw-500">{{ $product->sku_code }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex border-bottom py-2">
                                <div class="text-muted label-width">Category</div>
                                <div class="fw-500">{{ strtoupper($product->category->category_name ?? 'No Category') }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex border-bottom py-2">
                                <div class="text-muted label-width">Subcategory</div>
                                <div class="fw-500">{{ strtoupper($product->subcategory->subcategory_name ?? 'No Subcategory') }}</div>
                            </div>
                        </div>

                        <!-- 产品属性 -->
                        <div class="col-12">
                            <div class="d-flex border-bottom py-2">
                                <div class="text-muted label-width">Brand</div>
                                <div class="fw-500">{{ strtoupper(optional($product->brand)->brand_name ?? 'No Brand') }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex border-bottom py-2">
                                <div class="text-muted label-width">Color</div>
                                <div class="fw-500 d-flex align-items-center gap-2">
                                    @if($product->color && $product->color->hex_code)
                                        <div class="color-preview" style="background-color: {{ $product->color->hex_code }};"></div>
                                    @endif
                                    {{ strtoupper(optional($product->color)->color_name ?? 'No Color') }}
                                </div>
                            </div>
                        </div>

                        <!-- 库存信息 -->
                        <div class="col-12">
                            <div class="d-flex border-bottom py-2">
                                <div class="text-muted label-width">Stock</div>
                                <div class="fw-500">{{ $product->quantity }} Units</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex border-bottom py-2">
                                <div class="text-muted label-width">Location</div>
                                <div class="fw-500">
                                    Zone: {{ strtoupper(optional($product->zone)->zone_name ?? 'No Zone') }} |
                                    Rack: {{ strtoupper(optional($product->rack)->rack_number ?? 'No Rack') }}
                                </div>
                            </div>
                        </div>

                        <!-- 详细描述 -->
                        <div class="col-12">
                            <div class="d-flex border-bottom py-2">
                                <div class="text-muted label-width">Description</div>
                                <div class="fw-500">{{ $product->description }}</div>
                            </div>
                        </div>

                        <!-- 条形码信息 -->
                        @if($product->barcode)
                            <div class="col-12">
                                <div class="d-flex border-bottom py-2">
                                    <div class="text-muted label-width">Barcode</div>
                                    <div class="fw-500">
                                        <div class="text-center">
                                            <img src="{{ asset('assets/images/products/' . $product->barcode->barcode_image) }}"
                                                 alt="{{ $product->sku_code }}"
                                                 class="img-fluid barcode-image">
                                            <div class="mt-2">
                                                {{ $product->barcode->barcode_number }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <a href="{{ route('product.edit', $product->id) }}"
                           class="btn btn-outline-warning btn-lg flex-grow-1">
                            <i class="bi bi-pencil me-2"></i>Edit Product
                        </a>
                        <form action="{{ route('product.destroy', $product->id)}}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this product?');"
                              class="flex-grow-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-lg w-100">
                                <i class="bi bi-trash me-2"></i>Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeImage(src) {
    document.getElementById('mainImage').src = src;

    // Update thumbnail active state
    document.querySelectorAll('[onclick="changeImage(this.src)"]').forEach(thumb => {
        if (thumb.src === src) {
            thumb.classList.add('border-primary', 'border-2');
        } else {
            thumb.classList.remove('border-primary', 'border-2');
        }
    });
}
</script>
@endsection
