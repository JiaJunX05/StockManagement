<!-- CSS 檔案連結 -->
<link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">

<!-- Header -->
<nav class="navbar navbar-expand-xl custom-navbar">
    <div class="container">
        <!-- Logo部分 -->
        <a class="navbar-brand brand-logo" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-house-door-fill brand-icon me-2"></i>
            <span class="brand-text">CX330</span>
        </a>

        <!-- 移动端触发器 -->
        <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- 导航内容 -->
        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Staff -->
                <li class="nav-item dropdown menu-item">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-people-fill menu-icon me-2"></i><span>Staff</span>
                    </a>
                    <ul class="dropdown-menu custom-dropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('register') }}">
                                <i class="bi bi-person-plus-fill me-2"></i><span>Add Staff</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('user.list') }}">
                                <i class="bi bi-people me-2"></i><span>Staff List</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Category -->
                <li class="nav-item dropdown menu-item">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-tags-fill menu-icon me-2"></i><span>Category</span>
                    </a>
                    <ul class="dropdown-menu custom-dropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('category.create') }}">
                                <i class="bi bi-plus-square-fill me-2"></i><span>Add Category</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('category.list') }}">
                                <i class="bi bi-list-ul me-2"></i><span>Category List</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Zone -->
                <li class="nav-item dropdown menu-item">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-grid-fill menu-icon me-2"></i><span>Zone</span>
                    </a>
                    <ul class="dropdown-menu custom-dropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('zone.create') }}">
                                <i class="bi bi-plus-square-fill me-2"></i><span>Add Zone</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('zone.list') }}">
                                <i class="bi bi-list-ul me-2"></i><span>Zone List</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Rack -->
                <li class="nav-item dropdown menu-item">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-box-seam menu-icon me-2"></i><span>Rack</span>
                    </a>
                    <ul class="dropdown-menu custom-dropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('rack.create') }}">
                                <i class="bi bi-plus-square-fill me-2"></i><span>Add Rack</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('rack.list') }}">
                                <i class="bi bi-list-ul me-2"></i><span>Rack List</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Storack -->
                <li class="nav-item dropdown menu-item">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-archive-fill menu-icon me-2"></i><span>Storack</span>
                    </a>
                    <ul class="dropdown-menu custom-dropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('storack.create') }}">
                                <i class="bi bi-plus-square-fill me-2"></i><span>Add Storack</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('storack.list') }}">
                                <i class="bi bi-list-ul me-2"></i><span>Storack List</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Product -->
                <li class="nav-item dropdown menu-item">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-cart-fill menu-icon me-2"></i><span>Product</span>
                    </a>
                    <ul class="dropdown-menu custom-dropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('product.create') }}">
                                <i class="bi bi-plus-square-fill me-2"></i><span>Add Product</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('product.list') }}">
                                <i class="bi bi-list-ul me-2"></i><span>Product List</span>
                            </a>
                        </li>
                    </ul>
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
        </div>
    </div>
</nav>
