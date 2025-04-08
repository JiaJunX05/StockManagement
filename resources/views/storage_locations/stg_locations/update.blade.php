@extends("admin.layouts.app")

@section("title", "Update Location")
@section("content")

<link rel="stylesheet" href="{{ asset('assets/css/btn-primary.css') }}">
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
                        <h4 class="mb-0 fw-bold">Update Location</h4>
                        <p class="text-muted mb-0">Modify an existing location to better organize storage racks within designated zones</p>
                    </div>
                </div>
                <a href="{{ route('location.index') }}" class="btn btn-primary">
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
                            <i class="bi bi-geo-alt me-2"></i>Location Information
                        </h6>
                    </div>
                    <div class="preview-container flex-grow-1 d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <i class="bi bi-bezier2 text-primary" style="font-size: 8rem;"></i>
                            <p class="text-muted mt-3">Storage Location Management</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 右侧表单区域 -->
            <div class="col-md-7">
                <div class="card-body p-4">
                    <!-- 表单标题 -->
                    <h2 class="text-primary text-center mb-3">Update Location</h2>
                    <p class="text-muted text-center">Modify an existing location to better organize storage racks within designated zones</p>
                    <hr>

                    <!-- 表单内容 -->
                    <form action="{{ route('location.update', $location->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="zone_id" class="form-label fw-bold">Zone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-diagram-3 text-primary"></i>
                                </span>
                                <select class="form-select border-start-0" id="zone_id" name="zone_id" required>
                                    <option disabled value="">Select a zone</option>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}" {{ $location->zone_id == $zone->id ? 'selected' : '' }}>
                                            {{ strtoupper($zone->zone_name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="rack_id" class="form-label fw-bold">Rack</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-box-seam text-primary"></i>
                                </span>
                                <select class="form-select border-start-0" id="rack_id" name="rack_id" required>
                                    <option disabled value="">Select a rack</option>
                                    @foreach($racks as $rack)
                                        <option value="{{ $rack->id }}" {{ $location->rack_id == $rack->id ? 'selected' : '' }}>
                                            {{ strtoupper($rack->rack_number) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle-fill me-2"></i>Update Location
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
