@extends("staff.layouts.app")

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

    <!-- Welcome Section -->
    <div class="mb-5">
        <h1 class="display-4 text-primary font-weight-bold">Welcome to Admin Panel</h1>
        <p class="lead text-muted">Manage and organize your products efficiently.</p>
    </div>

    <div class="mb-3">
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search by SKU..." aria-label="Search" id="search-input">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>

    <!-- Users List Table -->
    <div class="table-responsive" id="table-container">
        <table id="list-table" class="table table-hover table-bordered table-striped">
            <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                <tr>
                    <th scope="col" class="fw-bold">Image</th>
                    <th scope="col" class="fw-bold">Name</th>
                    <th scope="col" class="fw-bold">Description</th>
                    <th scope="col" class="fw-bold">Price</th>
                    <th scope="col" class="fw-bold">Quantity</th>
                    <th scope="col" class="fw-bold">Category</th>
                    <th scope="col" class="fw-bold">Action</th>
                </tr>
            </thead>
            <tbody id="table-body"></tbody> <!-- 数据将通过 AJAX 动态填充 -->
        </table>
        <div id="result" class="text-center" style="display: none;">No results found.</div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-2">
        <ul id="pagination" class="pagination"></ul> <!-- 分页将通过 AJAX 动态填充 -->
    </div>
</div>
@endsection
