@extends("admin.layouts.app")

@section("title", "Update Storack")
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
                    <form action="{{ route('storack.update.submit', $storacks->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="zone_id" class="form-label fw-bold">Select Zone :</label>
                            <select class="form-select" id="zone_id" name="zone_id" required>
                                <option selected disabled value="">Select a zone</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}" {{ $storacks->zone_id == $zone->id ? 'selected' : '' }}>
                                        {{ strtoupper($zone->zone_name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="rack_id" class="form-label fw-bold">Select Rack :</label>
                            <select class="form-select" id="rack_id" name="rack_id" required>
                                <option selected disabled value="">Select a rack</option>
                                @foreach($racks as $rack)
                                    <option value="{{ $rack->id }}" {{ $storacks->rack_id == $rack->id ? 'selected' : '' }}>
                                        {{ strtoupper($rack->rack_number) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow-sm mt-3">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
