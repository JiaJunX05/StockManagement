@extends("admin.layouts.app")

@section("title", "Admin Panel")
@section("content")

<link rel="stylesheet" href="{{ asset('assets/css/locations.css') }}">
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
                            <i class="bi bi-geo-alt-fill text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">Location List Management</h3>
                            <p class="text-muted mb-0">Manage your locations</p>
                        </div>
                    </div>
                </div>

                <!-- 右侧功能区 -->
                <div class="col-12 col-md-6">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <select class="form-select" id="zone-filter" name="zone_id" style="width: auto; min-width: 200px;">
                            <option value="">All Zones</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ strtoupper($zone->zone_name) }}</option>
                            @endforeach
                        </select>
                        <select class="form-select" id="rack-filter" name="rack_id" style="width: auto; min-width: 200px;">
                            <option value="">All Racks</option>
                            @foreach($racks as $rack)
                                <option value="{{ $rack->id }}">{{ strtoupper($rack->rack_number) }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('location.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle-fill me-2"></i>
                            Add Location
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 列表表格 -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 10%"><div class="table-header">ID</div></th>
                            <th style="width: 15%"><div class="table-header">ZONE IMAGE</div></th>
                            <th style="width: 25%"><div class="table-header">STORAGE INFO</div></th>
                            <th style="width: 50%"><div class="table-header">ZONE LOCATION</div></th>
                            <th class="text-end pe-4" style="width: 10%"><div class="table-header">Actions</div></th>
                        </tr>
                    </thead>
                    <tbody id="table-body"></tbody>
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

<script>
function previewImage(src) {
    document.getElementById('previewImage').src = src;
    new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
}

$(document).ready(function () {
    const $tableBody = $("#table-body");
    const $pagination = $("#pagination");
    const $zoneFilter = $("#zone-filter");
    const $rackFilter = $("#rack-filter");
    const $prevPage = $("#prev-page");
    const $nextPage = $("#next-page");
    const $showingStart = $("#showing-start");
    const $showingEnd = $("#showing-end");
    const $totalCount = $("#total-count");

    function fetchLocations(page = 1, zone = "", rack = "") {
        $.get("{{ route('location.index') }}", {
            page,
            zone_id: zone,
            rack_id: rack,
            perPage: 10
        }, function (response) {
            if (response.data.length > 0) {
                renderLocations(response.data);
                updatePaginationInfo(response);
            } else {
                showNoResults();
            }
            generatePagination(response);
        });
    }

    function renderLocations(locations) {
        $tableBody.html(locations.map(location => `
            <tr>
                <td class="ps-4"><span class="text-muted">#${location.id}</span></td>
                <td>
                    ${location.zone && location.zone.zone_image ? `
                        <img src="/assets/${location.zone.zone_image}" alt="Zone Image"
                            class="img-fluid w-50 h-50 object-fit-cover preview-image cursor-pointer"
                            onclick="previewImage('/assets/${location.zone.zone_image}')">
                    ` : 'N/A'}
                </td>
                <td>
                    <div class="storage-info">
                        <div class="zone-info mb-1">
                            <span class="fw-medium">${location.zone ? location.zone.zone_name.toUpperCase() : 'N/A'}</span>
                        </div>
                        <div class="rack-info d-flex align-items-center">
                            <span class="fw-medium me-2">${location.rack ? location.rack.rack_number.toUpperCase() : 'N/A'}</span>
                            <span class="text-muted">(Capacity: ${location.rack ? location.rack.capacity : 'N/A'})</span>
                        </div>
                    </div>
                </td>
                <td><span class="fw-medium">${location.zone ? location.zone.location.toUpperCase() : 'N/A'}</span></td>
                <td class="text-end pe-4">
                    <div class="action-buttons">
                        <a href="{{ route('location.edit', '') }}/${location.id}" class="btn-action" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('location.destroy', '') }}/${location.id}" method="POST"
                            style="display: inline-block;"
                            onsubmit="return confirm('Are you sure you want to delete this location?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action delete" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        `).join(""));
    }

    function updatePaginationInfo(response) {
        const start = (response.current_page - 1) * response.per_page + 1;
        const end = Math.min(start + response.per_page - 1, response.total);

        $showingStart.text(response.total > 0 ? start : 0);
        $showingEnd.text(end);
        $totalCount.text(response.total);
    }

    function showNoResults() {
        $tableBody.empty();
        $showingStart.text('0');
        $showingEnd.text('0');
        $totalCount.text('0');
    }

    function generatePagination(data) {
        $("#pagination li:not(#prev-page):not(#next-page)").remove();

        let paginationHTML = '';

        $prevPage.toggleClass('disabled', data.current_page === 1);

        if (data.last_page > 7) {
            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page ||
                    (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                    paginationHTML += `
                        <li class="page-item ${i === data.current_page ? 'active' : ''}">
                            <a class="page-link pagination-btn" href="#" data-page="${i}">${i}</a>
                        </li>`;
                } else if (i === data.current_page - 2 || i === data.current_page + 2) {
                    paginationHTML += `
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>`;
                }
            }
        } else {
            for (let i = 1; i <= data.last_page; i++) {
                paginationHTML += `
                    <li class="page-item ${i === data.current_page ? 'active' : ''}">
                        <a class="page-link pagination-btn" href="#" data-page="${i}">${i}</a>
                    </li>`;
            }
        }

        $prevPage.after(paginationHTML);
        $nextPage.toggleClass('disabled', data.current_page === data.last_page);
    }

    // 事件监听
    $zoneFilter.on("change", function() {
        fetchLocations(1, $(this).val(), $rackFilter.val());
    });

    $rackFilter.on("change", function() {
        fetchLocations(1, $zoneFilter.val(), $(this).val());
    });

    $pagination.on("click", ".pagination-btn", function(e) {
        e.preventDefault();
        fetchLocations($(this).data("page"), $zoneFilter.val(), $rackFilter.val());
    });

    $prevPage.on('click', 'a', function(e) {
        e.preventDefault();
        if (!$(this).parent().hasClass('disabled')) {
            const currentPage = parseInt($('.page-item.active .page-link').data('page'));
            fetchLocations(currentPage - 1, $zoneFilter.val(), $rackFilter.val());
        }
    });

    $nextPage.on('click', 'a', function(e) {
        e.preventDefault();
        if (!$(this).parent().hasClass('disabled')) {
            const currentPage = parseInt($('.page-item.active .page-link').data('page'));
            fetchLocations(currentPage + 1, $zoneFilter.val(), $rackFilter.val());
        }
    });

    // 初始化加载
    fetchLocations();
});
</script>
@endsection
