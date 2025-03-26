@extends("admin.layouts.app")

@section("title", "Admin Panel")
@section("content")

<link rel="stylesheet" href="{{ asset('assets/css/users.css') }}">
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
            <div class="row justify-content-between align-items-center g-3">
                <div class="col-12 col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="bi bi-people-fill text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">User List Management</h3>
                            <p class="text-muted mb-0">Manage your staff members</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 text-md-end">
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-2"></i>
                        Add Staff
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
                        <input type="search"
                               class="form-control search-input"
                               id="search-input"
                               placeholder="Search by name, email or role...">
                    </div>
                </div>
                <div class="col-lg-3">
                    <select class="form-select filter-select" id="role-filter">
                        <option value="">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- 用户列表表格 -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 8%"><div class="table-header">ID</div></th>
                            <th style="width: 25%"><div class="table-header">USERNAME</div></th>
                            <th style="width: 30%"><div class="table-header">EMAIL</div></th>
                            <th style="width: 15%"><div class="table-header">ROLE</div></th>
                            <th class="text-end pe-4" style="width: 22%"><div class="table-header">ACTIONS</div></th>
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

<script>
$(document).ready(function () {
    const $tableBody = $("#table-body");
    const $result = $("#result");
    const $pagination = $("#pagination");
    const $searchInput = $("#search-input");
    const $roleFilter = $("#role-filter");
    const $prevPage = $("#prev-page");
    const $nextPage = $("#next-page");
    const $showingStart = $("#showing-start");
    const $showingEnd = $("#showing-end");
    const $totalCount = $("#total-count");

    function fetchUsers(page = 1, search = "", role = "") {
        $.get("{{ route('users') }}", {
            page,
            search,
            role,
            perPage: 10
        }, function (response) {
            if (response.data.length > 0) {
                renderUsers(response.data);
                updatePaginationInfo(response);
            } else {
                showNoResults();
            }
            generatePagination(response);
        });
    }

    function renderUsers(users) {
        $tableBody.html(users.map(user => `
            <tr>
                <td class="ps-4">
                    <span class="text-muted">#${user.id}</span>
                </td>
                <td>
                    <div class="user-info">
                        <div class="user-avatar"><i class="bi bi-person"></i></div>
                        <span class="fw-medium">${user.name.toUpperCase()}</span>
                    </div>
                </td>
                <td>
                    <span class="text-muted">${user.email}</span>
                </td>
                <td>
                    <span class="role-badge ${user.role.toLowerCase()}">
                        ${user.role.toUpperCase()}
                    </span>
                </td>
                <td class="text-end pe-4">
                    <div class="action-buttons">
                        <button class="btn-action" title="Change Role"><i class="bi bi-arrow-repeat"></i></button>
                        <button class="btn-action" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn-action delete" title="Delete" onclick="return confirm('Are you sure you want to delete this user?');"><i class="bi bi-trash"></i></button>
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
        $result?.show();
        $showingStart.text('0');
        $showingEnd.text('0');
        $totalCount.text('0');
    }

    function generatePagination(data) {
        // 清除现有的页码，但保留首尾的导航按钮
        $("#pagination li:not(#prev-page):not(#next-page)").remove();

        let paginationHTML = '';

        // 更新上一页按钮状态
        $prevPage.toggleClass('disabled', data.pagination.is_first_page);

        // 生成页码
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

        // 更新下一页按钮状态
        $nextPage.toggleClass('disabled', data.pagination.is_last_page);

        // 插入页码到分页控件
        $prevPage.after(paginationHTML);
    }

    // 事件监听
    $searchInput.on("keyup", function() {
        fetchUsers(1, $(this).val(), $roleFilter.val());
    });

    $roleFilter.on("change", function() {
        fetchUsers(1, $searchInput.val(), $(this).val());
    });

    $pagination.on("click", ".pagination-btn", function(e) {
        e.preventDefault();
        fetchUsers($(this).data("page"), $searchInput.val(), $roleFilter.val());
    });

    $prevPage.on('click', 'a', function(e) {
        e.preventDefault();
        if (!$(this).parent().hasClass('disabled')) {
            const currentPage = parseInt($('.page-item.active .page-link').data('page'));
            fetchUsers(currentPage - 1, $searchInput.val(), $roleFilter.val());
        }
    });

    $nextPage.on('click', 'a', function(e) {
        e.preventDefault();
        if (!$(this).parent().hasClass('disabled')) {
            const currentPage = parseInt($('.page-item.active .page-link').data('page'));
            fetchUsers(currentPage + 1, $searchInput.val(), $roleFilter.val());
        }
    });

    // 初始化加载
    fetchUsers();
});
</script>
@endsection
