@extends("admin.layouts.app")

@section("title", "Update Rack")
@section("content")

<style>
    .btn-primary {
        background: linear-gradient(to right, #4f46e5, #6366f1);
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #4338ca, #4f46e5);
        transform: translateY(-1px);
    }
</style>

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

    <!-- 页面标题卡片 -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-pencil-square text-primary fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">Update Rack</h4>
                        <p class="text-muted mb-0">Modify an existing rack to better organize and manage stock storage efficiently</p>
                    </div>
                </div>
                <a href="{{ route('rack.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- 主要内容卡片 -->
    <div class="card shadow-sm border-0">
        <div class="row g-0">
            <!-- 左侧图标区域 -->
            <div class="col-md-5">
                <div class="preview-section d-flex flex-column h-100 bg-light p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-box-seam me-2"></i>Rack Information
                        </h6>
                    </div>
                    <div class="preview-container flex-grow-1 d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <i class="bi bi-box-seam text-primary" style="font-size: 8rem;"></i>
                            <p class="text-muted mt-3">Storage Rack Management</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 右侧表单区域 -->
            <div class="col-md-7">
                <div class="card-body p-4">
                    <!-- 表单标题 -->
                    <h2 class="text-primary text-center mb-3">Update Rack</h2>
                    <p class="text-muted text-center">Modify an existing rack to better organize and manage stock storage efficiently</p>
                    <hr>

                    <!-- 表单内容 -->
                    <form action="{{ route('rack.update', $rack->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="rack_number" class="form-label fw-bold">Rack Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-hash text-primary"></i></span>
                                <input type="text" class="form-control border-start-0" id="rack_number" name="rack_number" value="{{ $rack->rack_number }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="capacity" class="form-label fw-bold">Capacity</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-boxes text-primary"></i></span>
                                <input type="number" class="form-control border-start-0" id="capacity" name="capacity" value="{{ $rack->capacity }}" placeholder="Enter rack capacity (default: 50)">
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-2"></i>
                                Leave empty to use default capacity (50)
                            </div>
                        </div>

                        <hr class="my-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle-fill me-2"></i>Update Rack
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
