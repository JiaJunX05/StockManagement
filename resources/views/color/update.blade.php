@extends("admin.layouts.app")

@section("title", "Update Color")
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
                        <h4 class="mb-0 fw-bold">Update Color</h4>
                        <p class="text-muted mb-0">Modify an existing color in your product catalog</p>
                    </div>
                </div>
                <a href="{{ route('color.index') }}" class="btn btn-primary">
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
                            <i class="bi bi-palette me-2"></i>Color Information
                        </h6>
                    </div>
                    <div class="preview-container flex-grow-1 d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <i class="bi bi-palette text-primary" style="font-size: 8rem;"></i>
                            <p class="text-muted mt-3">Color Management</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 右侧表单区域 -->
            <div class="col-md-7">
                <div class="card-body p-4">
                    <!-- 表单标题 -->
                    <h2 class="text-primary text-center mb-3">Update Color</h2>
                    <p class="text-muted text-center">Modify an existing color in your product catalog</p>
                    <hr>

                    <!-- 表单内容 -->
                    <form action="{{ route('color.update', $color->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="color_name" class="form-label fw-bold">Color Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-palette text-primary"></i></span>
                                <input type="text" class="form-control border-start-0" id="color_name" name="color_name" value="{{ $color->color_name }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="hex_code" class="form-label fw-bold">Hex Code</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-hash text-primary"></i></span>
                                <input type="text" class="form-control border-start-0" id="hex_code" name="hex_code" value="{{ $color->hex_code }}" placeholder="Enter hex code (e.g., #FF0000)" required>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-2"></i>
                                Enter the color's hex code (e.g., #FF0000 for red)
                            </div>
                            <!-- 添加颜色预览区域 -->
                            <div class="mt-3">
                                <div class="color-preview" id="color-preview" style="width: 100%; height: 60px; border-radius: 8px; background-color: {{ $color->hex_code }}; border: 1px solid #e5e7eb;"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="rgb_code" class="form-label fw-bold">RGB Code</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-palette text-primary"></i></span>
                                <input type="text" class="form-control border-start-0" id="rgb_code" name="rgb_code" value="{{ $color->rgb_code }}" placeholder="RGB code will be auto-generated" readonly>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-2"></i>
                                RGB code is automatically generated from the hex code
                            </div>
                        </div>

                        <hr class="my-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle-fill me-2"></i>Update Color
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
    <script src="{{ asset('assets/js/color-update.js') }}"></script>
@endsection
