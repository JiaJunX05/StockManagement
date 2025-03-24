@extends("admin.layouts.app")

@section("title", "Update Zone")
@section("content")

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
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
                    <h2 class="text-primary text-center fw-bold">Update Zone</h2>
                    <p class="text-muted text-center mb-4">Modify an existing zone to better categorize and manage stock locations efficiently.</p><hr>

                    <!-- Form -->
                    <form action="{{ route('zone.update.submit', $zones->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="zone_name" class="form-label fw-bold">Zone Name:</label>
                            <input type="text" class="form-control" id="zone_name" name="zone_name" value="{{ $zones->zone_name }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow-sm mt-3">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
