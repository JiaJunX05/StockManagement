@extends("admin.layouts.app")

@section("title", "Admin Panel")
@section("content")

<!-- JQuery CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="container text-center mt-5">
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

    <div class="container">
        <div class="row justify-content-between align-items-center mb-3">
            <div class="col-12 col-md-6 mb-2 mb-md-0 text-md-start">
                <h3 class="mb-0">User List Management</h3>
            </div>

            <div class="col-12 col-md-6 text-md-end">
                <form class="d-flex justify-content-md-end" role="search">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input class="form-control" type="search" placeholder="Search by Name..." aria-label="Search" id="search-input">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Users List Table -->
    <div class="table-responsive" id="table-container">
        <table id="list-table" class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="fw-bold text-nowrap w-10">ID</th>
                    <th scope="col" class="fw-bold text-nowrap w-25">Username</th>
                    <th scope="col" class="fw-bold text-nowrap w-25">Email</th>
                    <th scope="col" class="fw-bold text-nowrap w-15">Role</th>
                    <th scope="col" colspan="3" class="fw-bold text-nowrap w-25 text-center">Action</th>
                </tr>
            </thead>
            <tbody id="table-body"></tbody>
        </table>
        <div id="result" class="text-center" style="display: none;">No results found.</div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-2">
        <ul id="pagination" class="pagination"></ul>
    </div>
</div>

<script>
    $(document).ready(function () {
        const $tableBody = $("#table-body");
        const $result = $("#result");
        const $pagination = $("#pagination");
        const $searchInput = $("#search-input");

        function fetchUsers(page = 1, search = "") {
            $.get("{{ route('users') }}", { page, search }, function (response) {
                if (response.data.length > 0) {
                    $tableBody.html(response.data.map(user => `
                        <tr>
                            <td class="text-nowrap">${user.id}</td>
                            <td class="text-nowrap">${user.name.toUpperCase()}</td>
                            <td class="text-nowrap">${user.email}</td>
                            <td class="text-nowrap">${user.role.toUpperCase()}</td>
                            <td class="text-nowrap text-center">
                                <a href="#" class="btn btn-success btn-sm" style="width: 100px;">Change Role</a>
                                <a href="#" class="btn btn-warning btn-sm mx-1" style="width: 100px;">Edit</a>
                                <a href="#" class="btn btn-danger btn-sm mx-1" style="width: 100px;" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    `).join(""));
                    $result.hide();
                } else {
                    $tableBody.empty();
                    $result.show();
                }
                generatePagination(response);
            });
        }

        function generatePagination(data) {
            let paginationHTML = "";
            for (let i = 1; i <= data.last_page; i++) {
                paginationHTML += `<li class="page-item ${i === data.current_page ? 'active' : ''}">
                    <a class="page-link pagination-btn" href="#" data-page="${i}">${i}</a>
                </li>`;
            }
            $pagination.html(paginationHTML);
        }

        $searchInput.on("keyup", function () {
            fetchUsers(1, $(this).val());
        });

        $pagination.on("click", ".pagination-btn", function (e) {
            e.preventDefault();
            fetchUsers($(this).data("page"), $searchInput.val());
        });

        fetchUsers();
    });
</script>
@endsection
