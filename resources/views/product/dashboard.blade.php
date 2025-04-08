@extends("admin.layouts.app")

@section("title", "Product Management")
@section("content")

<meta name="product-index-url" content="{{ route('product.index') }}">
<link rel="stylesheet" href="{{ asset('assets/css/product-dashboard.css') }}">
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

    <div class="row">
        <!-- 左侧筛选栏 -->
        <div class="col-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <i class="bi bi-funnel me-2"></i>
                    <span>All Filters</span>
                </div>
                <div class="card-body p-0">
                    <!-- 主分类 -->
                    <div class="filter-section">
                        <div class="filter-header">
                            <h6 class="mb-0">Categories</h6>
                        </div>
                        <div id="filterCategory">
                            <div class="filter-list">
                                <a href="#" class="filter-item d-flex align-items-center">
                                    <i class="bi bi-grid-fill fs-5 me-3"></i>
                                    <span>All Categories</span>
                                </a>
                                @foreach($categories as $category)
                                <a href="#" class="filter-item d-flex align-items-center gap-3"
                                    data-category="{{ $category->id }}">
                                    @if($category->category_image)
                                        <img src="{{ asset('assets/images/' . $category->category_image) }}"
                                             alt="{{ $category->category_name }}"
                                             class="filter-image"
                                             onerror="this.onerror=null; this.src='{{ asset('assets/images/placeholder.png') }}';">
                                    @else
                                        <i class="bi bi-tag-fill fs-5 me-3"></i>
                                    @endif
                                    <span>{{ strtoupper($category->category_name) }}</span>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 子分类 -->
                    <div class="filter-section">
                        <div class="filter-header">
                            <h6 class="mb-0">Subcategories</h6>
                        </div>
                        <div id="filterSubcategory">
                            <div class="filter-list p-3">
                                @foreach($subcategories as $subcategory)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox"
                                           id="subcategory-{{ $subcategory->id }}"
                                           data-subcategory="{{ $subcategory->id }}">
                                    <label class="form-check-label d-flex justify-content-between align-items-center"
                                           for="subcategory-{{ $subcategory->id }}">
                                        {{ strtoupper($subcategory->subcategory_name) }}
                                        <span class="text-muted small">({{ $subcategory->products_count ?? '0' }}+)</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 品牌 -->
                    <div class="filter-section">
                        <div class="filter-header">
                            <h6 class="mb-0">Brands</h6>
                        </div>
                        <div id="filterBrand">
                            <div class="filter-list p-3">
                                @foreach($brands as $brand)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox"
                                           id="brand-{{ $brand->id }}"
                                           data-brand="{{ $brand->id }}">
                                    <label class="form-check-label d-flex justify-content-between align-items-center"
                                           for="brand-{{ $brand->id }}">
                                        {{ strtoupper($brand->brand_name) }}
                                        <span class="text-muted small">({{ $brand->products_count ?? '0' }}+)</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 右侧内容区 -->
        <div class="col-9">
            <!-- 页面标题和添加按钮 -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row justify-content-between align-items-center">
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                    <i class="bi bi-box-fill text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold">Product Management</h3>
                                    <p class="text-muted mb-0">Manage your products</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('product.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle-fill me-2"></i>
                                Add Product
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 搜索栏 -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="search" class="form-control border-start-0" id="search-input" placeholder="Search by SKU...">
                    </div>
                </div>
            </div>

            <!-- 产品列表 -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div id="product-card-container" class="row g-4" data-url="{{ route('product.index') }}" data-view-url="{{ route('product.view', ['id' => ':id']) }}"></div>
                    <div id="no-results" class="text-center py-3" style="display: none;">
                        <div class="text-muted">No products found</div>
                    </div>
                </div>
            </div>

            <!-- 分页 -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing <span class="fw-medium" id="showing-start">0</span>
                    to <span class="fw-medium" id="showing-end">0</span>
                    of <span class="fw-medium" id="total-count">0</span> entries
                </div>
                <nav aria-label="Page navigation">
                    <ul id="pagination" class="pagination pagination-sm mb-0">
                        <li class="page-item disabled" id="prev-page">
                            <a class="page-link" href="#" aria-label="Previous">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <li class="page-item disabled" id="next-page">
                            <a class="page-link" href="#" aria-label="Next">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
        <script src="{{ asset('assets/js/product-dashboard.js') }}"></script>
@endsection

