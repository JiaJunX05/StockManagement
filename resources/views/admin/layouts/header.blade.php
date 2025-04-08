<!-- CSS 檔案連結 -->
<link rel="stylesheet" href="{{ asset('assets/css/layouts/header.css') }}">

<!-- Header -->
<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <!-- Logo部分 -->
        <a class="navbar-brand brand-logo" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-house-door-fill brand-icon me-2"></i>
            <span class="brand-text">Stock Management</span>
        </a>

        <!-- 移动端触发器 -->
        <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
