@extends("admin.layouts.app")

@section("title", "Create Account")
@section("content")

<link rel="stylesheet" href="{{ asset('assets/css/auth/register.css') }}">
<div class="container py-4">
    <!-- 提示信息 -->
    @if(session("success"))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <span>{{ session("success") }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 页面标题卡片 -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-person-plus-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">Staff Registration</h4>
                        <p class="text-muted mb-0">Create a new account to access the system</p>
                    </div>
                </div>
                <a href="{{ route('user.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
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
                            <i class="bi bi-person-badge me-2"></i>Account Information
                        </h6>
                    </div>
                    <div class="preview-container flex-grow-1 d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <i class="bi bi-person-circle text-primary" style="font-size: 8rem;"></i>
                            <p class="text-muted mt-3">User Account Management</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 右侧表单区域 -->
            <div class="col-md-7">
                <div class="card-body p-4">
                    <!-- 表单标题 -->
                    <h2 class="text-primary text-center mb-3">Create Account</h2>
                    <p class="text-muted text-center">Fill in your information to create a new account</p>
                    <hr>

                    <!-- 注册表单 -->
                    <form action="{{ route('register.submit') }}" method="post">
                        @csrf

                        <!-- 姓名输入框 -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Staff Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person text-primary"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="name" name="name"
                                       placeholder="Enter your name" required>
                            </div>
                        </div>

                        <!-- 邮箱输入框 -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope text-primary"></i>
                                </span>
                                <input type="email" class="form-control border-start-0" id="email" name="email"
                                       placeholder="Enter your email" required>
                            </div>
                        </div>

                        <!-- 密码输入框 -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock text-primary"></i>
                                </span>
                                <input type="password" class="form-control border-start-0 border-end-0" id="password" name="password"
                                       placeholder="Enter password" required>
                                <span class="input-group-text bg-light border-start-0" role="button" onclick="togglePassword('password', 'togglePassword')">
                                    <i class="bi bi-eye-slash text-primary" id="togglePassword"></i>
                                </span>
                            </div>
                        </div>

                        <!-- 确认密码输入框 -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-shield-lock text-primary"></i>
                                </span>
                                <input type="password" class="form-control border-start-0 border-end-0" id="password_confirmation"
                                       name="password_confirmation" placeholder="Confirm password" required>
                                <span class="input-group-text bg-light border-start-0" role="button" onclick="togglePassword('password_confirmation', 'togglePasswordConfirm')">
                                    <i class="bi bi-eye-slash text-primary" id="togglePasswordConfirm"></i>
                                </span>
                            </div>
                        </div>

                        <!-- 角色选择 -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Your Role</label>
                            <div class="row g-3 role-cards">
                                <!-- Staff 选项 -->
                                <div class="col-6">
                                    <div class="card h-100 border">
                                        <label class="card-body d-flex align-items-center" style="cursor: pointer;">
                                            <input type="radio" name="role" value="staff" class="form-check-input me-3" checked>
                                            <div>
                                                <h6 class="card-title mb-1">Staff Member</h6>
                                                <p class="card-text text-muted small mb-0">Regular user access</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <!-- Admin 选项 -->
                                <div class="col-6">
                                    <div class="card h-100 border">
                                        <label class="card-body d-flex align-items-center" style="cursor: pointer;">
                                            <input type="radio" name="role" value="admin" class="form-check-input me-3">
                                            <div>
                                                <h6 class="card-title mb-1">Administrator</h6>
                                                <p class="card-text text-muted small mb-0">Full system access</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-person-plus-fill me-2"></i>Create Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 密码显示切换
    function togglePassword(passwordId, toggleId) {
        const password = document.getElementById(passwordId);
        const toggle = document.getElementById(toggleId);

        if (password.type === 'password') {
            password.type = 'text';
            toggle.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            password.type = 'password';
            toggle.classList.replace('bi-eye', 'bi-eye-slash');
        }
    }
</script>
@endsection