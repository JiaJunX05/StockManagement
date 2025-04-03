@extends("admin.layouts.app")

@section("title", "Category Mapping Management")
@section("content")

<link rel="stylesheet" href="{{ asset('assets/css/category/mapping.css') }}">
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
                            <i class="bi bi-diagram-2-fill text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">Category Mapping Management</h3>
                            <p class="text-muted mb-0">Manage category and subcategory relationships</p>
                        </div>
                    </div>
                </div>

                <!-- 右侧功能区 -->
                <div class="col-12 col-md-6">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <select class="form-select" id="category-filter" name="category_id" style="width: auto; min-width: 200px;">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ strtoupper($category->category_name) }}</option>
                            @endforeach
                        </select>
                        <select class="form-select" id="subcategory-filter" name="subcategory_id" style="width: auto; min-width: 200px;">
                            <option value="">All SubCategories</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ strtoupper($subcategory->subcategory_name) }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('mapping.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle-fill me-2"></i>
                            Add Mapping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 列表内容 -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div id="table-body" class="mapping-list">
                <!-- 内容将由 JavaScript 动态生成 -->
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
    const $mappingList = $("#table-body");
    const $pagination = $("#pagination");
    const $categoryFilter = $("#category-filter");
    const $subcategoryFilter = $("#subcategory-filter");
    const $prevPage = $("#prev-page");
    const $nextPage = $("#next-page");
    const $showingStart = $("#showing-start");
    const $showingEnd = $("#showing-end");
    const $totalCount = $("#total-count");

    function fetchMappings(page = 1) {
        $.get("{{ route('mapping.index') }}", {
            page,
            category_id: $categoryFilter.val(),
            subcategory_id: $subcategoryFilter.val(),
            perPage: 10
        }, function (response) {
            if (response.data.length > 0) {
                renderMappings(response.data);
                updatePaginationInfo(response);
            } else {
                showNoResults();
            }
            generatePagination(response);
        });
    }

    function renderMappings(mappings) {
        // 按 category 分组
        const groupedMappings = {};
        mappings.forEach(mapping => {
            if (!groupedMappings[mapping.category.id]) {
                groupedMappings[mapping.category.id] = {
                    category: mapping.category,
                    subcategories: []
                };
            }
            groupedMappings[mapping.category.id].subcategories.push({
                ...mapping.subcategory,
                mapping_id: mapping.id
            });
        });

        // 渲染分组后的数据
        $mappingList.html(Object.values(groupedMappings).map(group => `
            <div class="mapping-group">
                <div class="mapping-category">
                    <div class="d-flex align-items-center p-4">
                        <div class="category-icon-wrapper me-4">
                            ${group.category.category_image ? `
                                <img src="/assets/images/${group.category.category_image}" alt="Category Image"
                                     class="category-image" onclick="previewImage('/assets/images/${group.category.category_image}')">
                            ` : `
                                <div class="category-icon">
                                    <i class="bi bi-folder"></i>
                                </div>
                            `}
                        </div>
                        <div class="category-info">
                            <div class="category-title fw-bold">${group.category.category_name.toUpperCase()}</div>
                            <div class="category-meta">
                                <span class="subcategory-count">
                                    <i class="bi bi-diagram-2 me-2"></i>
                                    ${group.subcategories.length} Subcategories
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mapping-subcategories">
                    ${group.subcategories.map(subcategory => `
                        <div class="mapping-subcategory">
                            <div class="d-flex align-items-center justify-content-between p-3">
                                <div class="d-flex align-items-center">
                                    <div class="ms-4">
                                        <i class="bi bi-arrow-right-short text-primary me-2"></i>
                                        ${subcategory.subcategory_image ? `
                                            <img src="/assets/images/${subcategory.subcategory_image}" alt="SubCategory Image"
                                                 class="subcategory-image me-2" style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px;"
                                                 onclick="previewImage('/assets/images/${subcategory.subcategory_image}')">
                                        ` : `
                                            <div class="subcategory-icon me-2">
                                                <i class="bi bi-folder"></i>
                                            </div>
                                        `}
                                        <span class="text-muted">${subcategory.subcategory_name.toUpperCase()}</span>
                                    </div>
                                </div>
                                <div class="action-buttons">
                                    <a href="{{ route('mapping.edit', '') }}/${subcategory.mapping_id}" class="btn-action" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('mapping.destroy', '') }}/${subcategory.mapping_id}" method="POST"
                                        style="display: inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this mapping?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `).join(''));
    }

    function updatePaginationInfo(response) {
        const start = (response.current_page - 1) * response.per_page + 1;
        const end = Math.min(start + response.per_page - 1, response.total);

        $showingStart.text(response.total > 0 ? start : 0);
        $showingEnd.text(end);
        $totalCount.text(response.total);
    }

    function showNoResults() {
        $mappingList.empty();
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
    $categoryFilter.on("change", function() {
        fetchMappings(1);
    });

    $subcategoryFilter.on("change", function() {
        fetchMappings(1);
    });

    $pagination.on("click", ".pagination-btn", function(e) {
        e.preventDefault();
        fetchMappings($(this).data("page"));
    });

    $prevPage.on('click', 'a', function(e) {
        e.preventDefault();
        if (!$(this).parent().hasClass('disabled')) {
            const currentPage = parseInt($('.page-item.active .page-link').data('page'));
            fetchMappings(currentPage - 1);
        }
    });

    $nextPage.on('click', 'a', function(e) {
        e.preventDefault();
        if (!$(this).parent().hasClass('disabled')) {
            const currentPage = parseInt($('.page-item.active .page-link').data('page'));
            fetchMappings(currentPage + 1);
        }
    });

    // 初始化加载
    fetchMappings();
});
</script>
@endsection
