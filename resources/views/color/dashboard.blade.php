@extends("admin.layouts.app")

@section("title", "Color Management")
@section("content")

<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
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

    <!-- 页面标题和功能区 -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row justify-content-between align-items-center g-3">
                <!-- 左侧标题 -->
                <div class="col-12 col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="bi bi-palette-fill text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">Color Management</h3>
                            <p class="text-muted mb-0">Manage your colors</p>
                        </div>
                    </div>
                </div>

                <!-- 右侧功能区 -->
                <div class="col-12 col-md-6 text-md-end">
                    <a href="{{ route('color.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle-fill me-2"></i>
                        Add Color
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 搜索栏优化 -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-lg-9">
                    <div class="search-box">
                        <i class="bi bi-search search-icon"></i>
                        <input type="search" class="form-control search-input" id="search-input" placeholder="Search by color name...">
                    </div>
                </div>
                <div class="col-lg-3">
                    <select class="form-select filter-select" id="color-filter" name="color_id">
                        <option value="">All Colors</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}">{{ strtoupper($color->color_name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- 颜色列表表格 -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 10%"><div class="table-header">ID</div></th>
                            <th style="width: 15%"><div class="table-header">COLOR</div></th>
                            <th style="width: 15%"><div class="table-header">NAME</div></th>
                            <th style="width: 15%"><div class="table-header">HEX CODE</div></th>
                            <th style="width: 15%"><div class="table-header">RGB CODE</div></th>
                            <th class="text-end pe-4" style="width: 30%"><div class="table-header">ACTIONS</div></th>
                        </tr>
                    </thead>
                    <tbody id="table-body"
                           data-url="{{ route('color.index') }}"
                           data-edit-url="{{ route('color.edit', ['id' => ':id']) }}"
                           data-delete-url="{{ route('color.destroy', ['id' => ':id']) }}">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 分页和结果统计 -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="pagination-info text-muted">
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
                <!-- 页码由JS动态生成 -->
                <li class="page-item disabled" id="next-page">
                    <a class="page-link" href="#" aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection

@section("scripts")
    <script src="{{ asset('assets/js/color-dashboard.js') }}"></script>
@endsection
