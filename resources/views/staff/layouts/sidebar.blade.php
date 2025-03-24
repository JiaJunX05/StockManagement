<!-- Sidebar -->
<aside class="container offcanvas-lg offcanvas-start min-vh-100" style="background: linear-gradient(180deg, #2c3e50, #34495e);" tabindex="-1" id="sidebar">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title text-white">Admin Panel</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close" id="closesidebar"></button>
    </div>

    <div class="container offcanvas-body d-flex flex-column p-2" id="sidebarContent">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item bg-dark rounded-3 mb-2">
                <a href="{{ route('staff.dashboard') }}" class="nav-link w-100 text-white" aria-current="page">
                    <i class="bi bi-house-door-fill me-2" style="font-size: 1.2rem;"></i>
                    <span class="link-text fw-bold">StockManagement</span>
                </a>
            </li>

            <li class="nav-item">
                <button class="nav-link w-100 text-white d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#product">
                    <div>
                        <i class="bi bi-box-seam me-2" style="font-size: 1.2rem;"></i>
                        <span class="link-text fw-bold">Product</span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <ul id="product" class="list-unstyled collapse">
                    <li>
                        <a class="accordion-item nav-link text-white" href="{{ route('product.create') }}">
                            <i class="bi bi-plus-circle me-2"></i>
                            <span class="link-text fw-bold">Add Product</span>
                        </a>
                    </li>
                    <li>
                        <a class="accordion-item nav-link text-white" href="{{ route('product.list') }}">
                            <i class="bi bi-card-list me-2"></i>
                            <span class="link-text fw-bold">Product List</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger d-flex align-items-center gap-2 fw-bold w-100">
                <i class="bi bi-box-arrow-right me-2"></i> Sign Out
            </button>
        </form>
    </div>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sidebar = document.getElementById("sidebar");
        const sidebarContent = document.getElementById("sidebarContent");
        const closeButton = document.getElementById("closesidebar");
        const sidebarInstance = new bootstrap.Offcanvas(sidebar); // 初始化 Bootstrap Offcanvas

        // 默认宽度设置为 250px
        sidebar.style.width = "250px";

        // 监听 offcanvas 的显示事件
        sidebar.addEventListener("show.bs.offcanvas", function () {
            sidebar.style.width = "350px"; // 显示时宽度为 100%
        });

        // 监听 offcanvas 的隐藏事件
        sidebar.addEventListener("hidden.bs.offcanvas", function () {
            sidebar.style.width = "250px"; // 隐藏后恢复为 250px
        });

        // 关闭按钮事件
        closeButton.addEventListener("click", function () {
            sidebarInstance.hide(); // 关闭侧边栏
        });
    });
</script>
