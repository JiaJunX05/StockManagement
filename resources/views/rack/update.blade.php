@extends("admin.layouts.app")

@section("title", "Update Rack")
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
                    <h2 class="text-primary text-center fw-bold">Update Rack</h2>
                    <p class="text-muted text-center mb-4">Modify an existing rack to better organize and manage stock locations efficiently.</p><hr>

                    <!-- Form -->
                    <form action="{{ route('rack.update.submit', $racks->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="rack_number" class="form-label fw-bold">Rack Number :</label>
                            <input type="text" class="form-control" id="rack_number" name="rack_number" value="{{ $racks->rack_number}}" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow-sm mt-3">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
