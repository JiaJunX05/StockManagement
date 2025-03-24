@extends("staff.layouts.app")

@section("title", "Stock Product")
@section("content")

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="row g-0">

                    <div class="col-md-5 d-flex align-items-center justify-content-center p-3 bg-light">
                        <img src="{{ asset('assets/' . $product->feature) }}"
                            alt="{{ $product->name }}" class="img-fluid" id="preview-image" style="max-width: 100%; max-height: 300px; object-fit: contain;">
                    </div>

                    <div class="col-md-7">
                        <div class="card-body p-4">
                            <div class="container text-center">
                                <!-- Success Alert -->
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <!-- Error Alert -->
                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                            </div>

                            <!-- Form Title -->
                            <div class="text-center">
                                <h2 class="text-primary mb-3">Stock Product</h2>
                                <p class="text-muted">Manage and update inventory effortlessly</p><hr>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-bold">Product Name:</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label fw-bold">Product Quantity:</label>
                                        <input type="text" class="form-control" id="quantity" name="quantity" min="1" value="{{ $product->quantity }} Units" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock In & Out -->
                            <h3 class="text-primary">Stock In & Out</h3>
                            <p class="text-muted">Easily manage and update stock levels.</p><hr>

                            <form action="{{ route('stock.submit', $product->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Product Quantity:</label>
                                    <div class="input-group">
                                        @if ($product->quantity == 0)
                                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="1"
                                                placeholder="Enter Your Stock Quantity" required>
                                        @else
                                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="1"
                                                max="{{ request('status') === 'stock_out' ? $product->quantity : '' }}" placeholder="Enter Stock Quantity" required>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="stock_status" class="form-label">Stock Status:</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <input type="radio" name="status" id="stock_in" value="stock_in" required>
                                                </div>
                                                <label for="stock_in" class="form-control">Stock In</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    @if ($product->quantity == 0)
                                                        <input type="radio" name="status" id="stock_out" value="stock_out" disabled>
                                                    @else
                                                        <input type="radio" name="status" id="stock_out" value="stock_out" required>
                                                    @endif
                                                </div>
                                                <label for="stock_out" class="form-control">Stock Out</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success w-100">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
