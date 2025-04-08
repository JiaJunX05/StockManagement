<!-- CSS 檔案連結 -->
<link rel="stylesheet" href="{{ asset('assets/css/layouts/sidebar.css') }}">

<!-- 侧边栏 -->
<aside class="sidebar">
    <!-- Logo部分 -->
    <div class="sidebar-header">
        <a class="brand-logo" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-house-door-fill brand-icon me-2"></i>
            <span class="brand-text">Admin Panel</span>
        </a>
        <button class="sidebar-toggle d-xl-none">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- 侧边栏导航 -->
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <!-- Staff -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}"
                   href="{{ route('user.index') }}">
                    <i class="bi bi-people-fill menu-icon me-2"></i>
                    <span>Staff Management</span>
                </a>
            </li>

            <!-- Storage Location -->
            <li class="nav-item">
                <button class="nav-link {{ request()->routeIs(['zone.*', 'rack.*', 'location.*']) ? 'active' : '' }} has-dropdown w-100 text-start"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#storageCollapse">
                    <i class="bi bi-hdd menu-icon me-2"></i>
                    <span>Storage Location</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </button>
                <div class="collapse nav-collapse {{ request()->routeIs(['zone.*', 'rack.*', 'location.*']) ? 'show' : '' }}"
                     id="storageCollapse">
                    <ul class="nav-list sub-nav">
                        <li>
                            <a href="{{ route('zone.index') }}"
                               class="nav-link {{ request()->routeIs('zone.*') ? 'active' : '' }}">
                                <i class="bi bi-map me-2"></i>
                                <span>Manage Zone</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rack.index') }}"
                               class="nav-link {{ request()->routeIs('rack.*') ? 'active' : '' }}">
                                <i class="bi bi-bar-chart-steps me-2"></i>
                                <span>Manage Rack</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('location.index') }}"
                               class="nav-link {{ request()->routeIs('location.*') ? 'active' : '' }}">
                                <i class="bi bi-bezier2 me-2"></i>
                                <span>Storage Location</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Categories -->
            <li class="nav-item">
                <button class="nav-link {{ request()->routeIs(['category.*', 'subcategory.*', 'mapping.*']) ? 'active' : '' }} has-dropdown w-100 text-start"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#categoryCollapse">
                    <i class="bi bi-diagram-3 menu-icon me-2"></i>
                    <span>Category Mapping</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </button>
                <div class="collapse nav-collapse {{ request()->routeIs(['category.*', 'subcategory.*', 'mapping.*']) ? 'show' : '' }}"
                     id="categoryCollapse">
                    <ul class="nav-list sub-nav">
                        <li>
                            <a href="{{ route('category.index') }}"
                               class="nav-link {{ request()->routeIs('category.*') ? 'active' : '' }}">
                                <i class="bi bi-folder-plus me-2"></i>
                                <span>Manage Category</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('subcategory.index') }}"
                               class="nav-link {{ request()->routeIs('subcategory.*') ? 'active' : '' }}">
                                <i class="bi bi-folder-symlink me-2"></i>
                                <span>Manage Subcategory</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('mapping.index') }}"
                               class="nav-link {{ request()->routeIs('mapping.*') ? 'active' : '' }}">
                                <i class="bi bi-folder-fill me-2"></i>
                                <span>Category Mapping</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Brand & Color -->
            <li class="nav-item">
                <button class="nav-link {{ request()->routeIs(['brand.*', 'color.*']) ? 'active' : '' }} has-dropdown w-100 text-start"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#attributeCollapse">
                    <i class="bi bi-palette menu-icon me-2"></i>
                    <span>Brand & Color</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </button>
                <div class="collapse nav-collapse {{ request()->routeIs(['brand.*', 'color.*']) ? 'show' : '' }}"
                     id="attributeCollapse">
                    <ul class="nav-list sub-nav">
                        <li>
                            <a href="{{ route('brand.index') }}"
                               class="nav-link {{ request()->routeIs('brand.*') ? 'active' : '' }}">
                                <i class="bi bi-tag me-2"></i>
                                <span>Manage Brand</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('color.index') }}"
                               class="nav-link {{ request()->routeIs('color.*') ? 'active' : '' }}">
                                <i class="bi bi-palette-fill me-2"></i>
                                <span>Manage Color</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Products -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('product.*') ? 'active' : '' }}"
                   href="{{ route('product.index') }}">
                    <i class="bi bi-cart-fill menu-icon me-2"></i>
                    <span>Product Management</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- 底部登出按钮 -->
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn custom-logout-btn w-100">
                <i class="bi bi-box-arrow-right me-2"></i>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</aside>

<!-- 移动端遮罩层 -->
<div class="sidebar-overlay"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 获取元素
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const dropdownButtons = document.querySelectorAll('.nav-link.has-dropdown');
    const headerToggle = document.querySelector('.navbar-toggler');

    // 移动端侧边栏切换
    function toggleSidebar() {
        sidebar.classList.toggle('show');
        document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
    }

    // 点击切换按钮
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    // 点击遮罩层
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }

    // 点击header按钮
    if (headerToggle) {
        headerToggle.addEventListener('click', toggleSidebar);
    }

    // 处理子菜单展开/收起
    dropdownButtons.forEach(button => {
        const collapseId = button.getAttribute('data-bs-target');
        const collapseElement = document.querySelector(collapseId);

        if (collapseElement) {
            // 使用 Bootstrap 的 collapse 事件
            collapseElement.addEventListener('show.bs.collapse', function () {
                const arrow = button.querySelector('.bi-chevron-down');
                if (arrow) {
                    arrow.style.transform = 'rotate(-180deg)';
                }
            });

            collapseElement.addEventListener('hide.bs.collapse', function () {
                const arrow = button.querySelector('.bi-chevron-down');
                if (arrow) {
                    arrow.style.transform = 'rotate(0deg)';
                }
            });

            // 初始化箭头状态
            const arrow = button.querySelector('.bi-chevron-down');
            if (arrow && collapseElement.classList.contains('show')) {
                arrow.style.transform = 'rotate(-180deg)';
            }
        }
    });
});
</script>
