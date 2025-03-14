<!-- Header -->
<nav class="navbar navbar-expand-lg bg-light shadow-sm border-bottom border-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('staff.dashboard') }}">
            <i class="bi bi-house-door-fill me-2"></i> Inventory System
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href=""><i class="bi bi-bag-plus-fill me-2"></i> Add Product</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger d-flex align-items-center gap-2 fw-bold w-100">
                            <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
