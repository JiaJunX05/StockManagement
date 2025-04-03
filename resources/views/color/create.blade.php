@extends("admin.layouts.app")

@section("title", "Create Color")
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
                        <i class="bi bi-plus-circle-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">Create Color</h4>
                        <p class="text-muted mb-0">Add a new color to your product catalog</p>
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
                    <h2 class="text-primary text-center mb-3">Create Color</h2>
                    <p class="text-muted text-center">Add a new color to your product catalog</p>
                    <hr>

                    <!-- 表单内容 -->
                    <form action="{{ route('color.store') }}" method="post">
                        @csrf

                        <div class="mb-4">
                            <label for="color_name" class="form-label fw-bold">Color Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-palette text-primary"></i></span>
                                <input type="text" class="form-control border-start-0" id="color_name" name="color_name" placeholder="Enter color name" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="hex_code" class="form-label fw-bold">Hex Code</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-hash text-primary"></i></span>
                                <input type="text" class="form-control border-start-0" id="hex_code" name="hex_code" placeholder="Enter hex code (e.g., #FF0000)" required>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-2"></i>
                                Enter the color's hex code (e.g., #FF0000 for red)
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="rgb_code" class="form-label fw-bold">RGB Code</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-palette text-primary"></i></span>
                                <input type="text" class="form-control border-start-0" id="rgb_code" name="rgb_code" placeholder="RGB code will be auto-generated" readonly>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-2"></i>
                                RGB code is automatically generated from the hex code
                            </div>
                        </div>

                        <hr class="my-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle-fill me-2"></i>Create Color
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hexInput = document.getElementById('hex_code');
    const rgbInput = document.getElementById('rgb_code');

    function hexToRgb(hex) {
        // Remove the hash if present
        hex = hex.replace('#', '');

        // Parse the hex values
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);

        return `rgb(${r}, ${g}, ${b})`;
    }

    function validateHex(hex) {
        // Remove the hash if present
        hex = hex.replace('#', '');

        // Check if it's a valid 6-digit hex code
        return /^[0-9A-Fa-f]{6}$/.test(hex);
    }

    hexInput.addEventListener('input', function() {
        const hex = this.value;

        if (validateHex(hex)) {
            const rgb = hexToRgb(hex);
            rgbInput.value = rgb;
        } else {
            rgbInput.value = '';
        }
    });
});
</script>
@endsection
