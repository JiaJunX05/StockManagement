@extends("admin.layouts.app")

@section("title", "Location Management")
@section("content")

<meta name="location-index-url" content="{{ route('location.index') }}">
<link rel="stylesheet" href="{{ asset('assets/css/location.css') }}">
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

    <!-- 页面标题和添加按钮 -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-geo-alt-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">Storage Location Management</h4>
                        <p class="text-muted mb-0">Manage your storage locations</p>
                    </div>
                </div>
                <a href="{{ route('location.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle-fill me-2"></i>
                    Add Location
                </a>
            </div>
        </div>
    </div>

    <!-- 主要内容区域 -->
    <div class="card border-0 shadow-sm">
        <div class="card-body pb-0">
            <div class="d-flex justify-content-end gap-2 mb-4">
                <select class="form-select form-select-sm w-auto" id="zone-filter" name="zone_id" style="min-width: 180px;">
                    <option value="">All Zones</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}">{{ strtoupper($zone->zone_name) }}</option>
                    @endforeach
                </select>
                <select class="form-select form-select-sm w-auto" id="rack-filter" name="rack_id" style="min-width: 180px;">
                    <option value="">All Racks</option>
                    @foreach($racks as $rack)
                        <option value="{{ $rack->id }}">{{ strtoupper($rack->rack_number) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card-body p-0">
            <div id="table-body" class="location-list"
                data-url="{{ route('location.index') }}"
                data-edit-url="{{ route('location.edit', ['id' => ':id']) }}"
                data-delete-url="{{ route('location.destroy', ['id' => ':id']) }}">
            </div>
            <div id="no-results" class="text-center py-4" style="display: none;">
                <div class="text-muted">No locations found</div>
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
                <li class="page-item disabled" id="next-page">
                    <a class="page-link" href="#" aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- 图片预览 Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="previewImage" src="" alt="Preview" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
    <script src="{{ asset('assets/js/location-dashboard.js') }}"></script>
@endsection
