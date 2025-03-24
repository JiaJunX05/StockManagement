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

    <!-- Error Alert -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <h3 class="text-md-start mb-0">Storack List Management</h3>

        <form class="d-flex gap-3 align-items-center" role="search" id="search-form">
            <select class="form-select" id="zone_id" name="zone_id" style="width: 150px;">
                <option selected value="">Select a Zone</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}">{{ strtoupper($zone->zone_name) }}</option>
                @endforeach
            </select>

            <select class="form-select" id="rack_id" name="rack_id" style="width: 150px;">
                <option selected value="">Select a Rack</option>
                @foreach($racks as $rack)
                    <option value="{{ $rack->id }}">{{ strtoupper($rack->rack_number) }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <hr>

    <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover table-striped">
            <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                <tr>
                    <th scope="col" class="fw-bold col-1">ID</th>
                    <th scope="col" class="fw-bold col-4">Zone Name</th>
                    <th scope="col" class="fw-bold col-4">Rack Number</th>
                    <th scope="col" colspan="2" class="fw-bold col-3">Action</th>
                </tr>
            </thead>
            <tbody id="table-body"></tbody>
        </table>

        <div id="no-results" class="text-center py-3" style="display: none;">No results found.</div>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation example" class="d-flex justify-content-center mt-3">
        <ul id="pagination" class="pagination"></ul>
    </nav>
</div>

<script>
    $(document).ready(function() {
        let currentZone = '';
        let currentRack = '';

        // 初始加载表格
        loadTable(1);

        // 选择框变化时触发搜索
        $('#zone_id, #rack_id').on('change', function() {
            currentZone = $('#zone_id').val();
            currentRack = $('#rack_id').val();
            loadTable(1);
        });

        function loadTable(page) {
            $.ajax({
                url: "{{ route('storack.list') }}",
                type: 'GET',
                data: {
                    page: page,
                    zone_id: currentZone,
                    rack_id: currentRack,
                    length: 10
                },
                success: function(response) {
                    renderTable(response.data);
                    renderPagination(response.current_page, response.last_page);
                    $('#no-results').toggle(response.data.length === 0);
                },
                error: function(xhr) {
                    console.error('Error loading storacks:', xhr);
                }
            });
        }

        function renderTable(data) {
            let tableBody = $('#table-body');
            tableBody.empty();

            data.forEach(storack => {
                tableBody.append(`
                    <tr>
                        <td>${storack.id}</td>
                        <td>Zone: ${storack.zone ? storack.zone.zone_name.toUpperCase() : 'N/A'}</td>
                        <td>Rack: ${storack.rack ? storack.rack.rack_number.toUpperCase() : 'N/A'}</td>
                        <td><a href="{{ route('storack.update', '') }}/${storack.id}" class="btn btn-warning btn-sm" style="width: 100px;">Edit</a></td>
                        <td>
                            <form action="{{ route('storack.destroy', '') }}/${storack.id}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Storacks?');" class="w-100">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm" style="width: 100px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                `);
            });
        }

        function renderPagination(currentPage, lastPage) {
            let pagination = $('#pagination');
            pagination.empty();

            // 上一页
            if (currentPage > 1) {
                pagination.append(`
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                    </li>
                `);
            }

            // 页码
            for (let i = Math.max(1, currentPage - 2); i <= Math.min(lastPage, currentPage + 2); i++) {
                pagination.append(`
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `);
            }

            // 下一页
            if (currentPage < lastPage) {
                pagination.append(`
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                    </li>
                `);
            }

            // 分页点击事件
            $('.page-link').on('click', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                loadTable(page);
            });
        }
    });
</script>
@endsection
