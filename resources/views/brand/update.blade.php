@extends("admin.layouts.app")

@section("title", "Update Brand")
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
                        <i class="bi bi-pencil-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">Update Brand</h4>
                        <p class="text-muted mb-0">Modify existing brand information</p>
                    </div>
                </div>
                <a href="{{ route('brand.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- 主要内容卡片 -->
    <div class="card shadow-sm border-0">
        <div class="row g-0">
            <!-- 左侧预览区域 -->
            <div class="col-md-5">
                <div class="preview-section d-flex flex-column h-100 bg-light p-3">
                    <!-- 预览标题 -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-image me-2"></i>Preview
                        </h6>
                        <span class="badge bg-white text-dark border px-3 py-2">800 x 600</span>
                    </div>

                    <!-- 预览图片 -->
                    <div class="preview-container flex-grow-1 d-flex align-items-center justify-content-center">
                        <img src="{{ asset('assets/images/' . $brand->brand_image) }}" alt="Preview" id="preview-image"
                            class="img-fluid rounded-3" style="max-width: 100%; max-height: 280px; object-fit: contain;">
                    </div>
                </div>
            </div>

            <!-- 右侧表单区域 -->
            <div class="col-md-7">
                <div class="card-body p-4">
                    <!-- 表单标题 -->
                    <h2 class="text-primary text-center mb-3">Update Brand</h2>
                    <p class="text-muted text-center">Modify brand information to better manage products</p>
                    <hr>

                    <!-- 表单内容 -->
                    <form action="{{ route('brand.update', $brand->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="brand_image" class="form-label fw-bold">Brand Image</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-upload text-primary"></i></span>
                                <input type="file" class="form-control border-start-0" id="brand_image" name="brand_image">
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-2"></i>
                                Supported formats: JPEG, PNG, JPG, GIF
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="brand_name" class="form-label fw-bold">Brand Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-tag text-primary me-2"></i></span>
                                <input type="text" class="form-control border-start-0" id="brand_name" name="brand_name"
                                       value="{{ $brand->brand_name }}" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle-fill me-2"></i>Update Brand
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // This code is used to preview the selected image in the brand form
    const previewIcon = document.querySelector('#preview-icon');    // 默认显示的图标
    const previewImage = document.querySelector('#preview-image');  // 预览图片的 <img> 标签
    const input = document.querySelector('#brand_image');           // 文件上传的 <input>

    input.addEventListener('change', () => {
        const file = input.files[0]; // 获取用户选择的文件
        if (file && file.type.startsWith('image/')) { // 检查是否为图片文件
            previewImage.src = URL.createObjectURL(file); // 设置预览图片的 src 属性
            previewIcon.classList.add('d-none');         // 隐藏图标
            previewImage.classList.remove('d-none');     // 显示图片
        } else {
            alert('Please select a valid image file!'); // 提示用户选择正确的文件
            // 重置预览状态
            previewIcon.classList.remove('d-none');     // 显示图标
            previewImage.classList.add('d-none');       // 隐藏图片
        }
    });
</script>
@endsection

