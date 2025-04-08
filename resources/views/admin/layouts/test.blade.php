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

        {{-- <!-- 导航内容 -->
        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Staff -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.index') }}">
                        <i class="bi bi-people-fill menu-icon me-2"></i><span>Staff Management</span>
                    </a>
                </li>

                <!-- Zone & Rack Management -->
                <li class="nav-item dropdown menu-item">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-hdd menu-icon me-2"></i><span>Storage Location</span>
                    </a>
                    <ul class="dropdown-menu custom-dropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('zone.index') }}">
                                <i class="bi bi-map me-2"></i><span>Manage Zone</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('rack.index') }}">
                                <i class="bi bi-bar-chart-steps me-2"></i><span>Manage Rack</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('location.index') }}">
                                <i class="bi bi-bezier2 me-2"></i><span>Storage Location</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Category & Subcategory Management -->
                <li class="nav-item dropdown menu-item">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-diagram-3 menu-icon me-2"></i><span>Category Mapping</span>
                    </a>
                    <ul class="dropdown-menu custom-dropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('category.index') }}">
                                <i class="bi bi-folder-plus me-2"></i><span>Manage Category</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('subcategory.index') }}">
                                <i class="bi bi-folder-symlink me-2"></i><span>Manage Subcategory</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('mapping.index') }}">
                                <i class="bi bi-folder-fill me-2"></i><span>Category Mapping</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Brand & Color Management -->
                <li class="nav-item dropdown menu-item">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-palette menu-icon me-2"></i><span>Brand & Color</span>
                    </a>
                    <ul class="dropdown-menu custom-dropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('brand.index') }}">
                                <i class="bi bi-tag me-2"></i><span>Manage Brand</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('color.index') }}">
                                <i class="bi bi-palette-fill me-2"></i><span>Manage Color</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Product -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('product.index') }}">
                        <i class="bi bi-cart-fill menu-icon me-2"></i><span>Product Management</span>
                    </a>
                </li>
            </ul>

            <!-- 登出按钮 -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit" class="btn custom-logout-btn">
                            <i class="bi bi-box-arrow-right me-2"></i><span>Sign Out</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div> --}}
    </div>
</nav>
