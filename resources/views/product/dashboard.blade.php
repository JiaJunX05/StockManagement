@extends("admin.layouts.app")

@section("title", "Admin Panel")
@section("content")

<div class="container text-center mt-5">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container">
        <div class="row justify-content-between align-items-center mb-3">
            <div class="col-12 col-md-6 mb-2 mb-md-0 text-md-start">
                <h3 class="mb-0">Product List Management</h3>
            </div>

            <div class="col-12 col-md-6 text-md-end">
                <form class="d-flex justify-content-md-end" role="search" id="search-form">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input class="form-control" type="search" placeholder="Search by SKU..." aria-label="Search" id="search-input" autofocus>
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 卡片列表 -->
    <div id="product-card-container" class="row g-4"></div>

    <div id="no-results" class="text-center py-3" style="display: none;">No results found.</div>

    <!-- Pagination -->
    <nav aria-label="Page navigation example" class="d-flex justify-content-center mt-3">
        <ul id="pagination" class="pagination"></ul>
    </nav>
</div>

<script>
    $(document).ready(function() {
        let currentSearch = '';

        // 初始加载表格
        loadTable(1);

        // 搜索表单提交事件
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
        });


        // 表单提交事件
        $('#search-form').on('input', function(e) {
            e.preventDefault();
            currentSearch = $('#search-input').val();
            loadTable(1);
        });

        function loadTable(page) {
            $.ajax({
                url: "{{ route('product.list') }}",
                type: 'GET',
                data: {
                    page: page,
                    search: currentSearch,
                    length: 10
                },
                success: function(response) {
                    console.log(response); // 调试返回数据
                    renderCards(response.data);
                    renderPagination(response.current_page, response.last_page);

                    // 显示/隐藏无结果提示
                    $('#no-results').toggle(response.data.length === 0);
                },
                error: function(xhr) {
                    console.error('Error loading table:', xhr);
                }
            });
        }

        function renderCards(data) {
            let container = $('#product-card-container');
            container.empty();

            data.forEach(product => {
                let barcodeImage = product.barcode && product.barcode.barcode_image
                    ? `/assets/${product.barcode.barcode_image}`
                    : '/assets/default.jpg';

                let barcodeNumber = product.barcode && product.barcode.barcode_number
                    ? product.barcode.barcode_number
                    : 'N/A';

                let productFeature = product.feature ? `/assets/${product.feature}` : '/assets/default.jpg';

                container.append(`
                    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                        <div class="card shadow-sm border-0 w-100">
                            <div class="card-header bg-light d-flex flex-column align-items-start p-3" style="border-bottom: 2px solid #007bff;">
                                <h5 class="card-title mb-1" style="font-weight: bold; color: #333;">${product.sku_code}</h5>
                            </div>

                            <div class="position-relative text-center d-flex align-items-center justify-content-center mb-3">
                                <img src="${productFeature}" alt="Product Feature" class="img-fluid mt-3" style="width: 150px; object-fit: cover;">
                            </div>

                            <div class="card-body">
                                <img src="${barcodeImage}" class="img-fluid" style="max-width: 200px;"> <br>
                                ${barcodeNumber}

                                <div class="card-footer text-body-secondary mt-3 p-0 d-grid">
                                    <a href="{{ route('product.view', '') }}/${product.id}" class="btn btn-success w-100">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });
        }

        function renderPagination(currentPage, lastPage) {
            let pagination = $('#pagination');
            pagination.empty();

            let paginationHtml = `
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                            <a class="page-link" href="#" aria-label="Previous" data-page="${currentPage - 1}">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>`;

            for (let i = 1; i <= lastPage; i++) {
                paginationHtml += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>`;
            }

            paginationHtml += `
                        <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                            <a class="page-link" href="#" aria-label="Next" data-page="${currentPage + 1}">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>`;

            pagination.html(paginationHtml);

            // 绑定点击事件
            $('.page-link').on('click', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page >= 1 && page <= lastPage) {
                    loadTable(page);
                }
            });
        }
    });
</script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endsection
