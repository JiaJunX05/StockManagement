@extends("admin.layouts.app")

@section("title", "Reset Password")
@section("content")
    <div class="container text-center position-absolute top-50 start-50 translate-middle">
        <div class="col justify-content-center mb-4">
            <img src="https://static-00.iconduck.com/assets.00/laravel-icon-995x1024-dk77ahh4.png" alt="" class="img-fluid" style="width: 100px;">
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">

            <!-- Success Alert -->
            @if(session("success"))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session("success") }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session("error"))
                {{ session("error") }}
            @endif
                <h1 class="pb-2 mb-4 text-primary-emphasis border-bottom border-danger" style="font-weight: 700;">Reset Password</h1>
                <form action="{{route('password.update')}}" method="post">
                    @csrf
                    <div class="form-floating mb-3 mt-3">
                        <input type="hidden" name="token" value="{{ request()->route('token') }}">
                    </div>
                    @error('token')
                    {{$message}}
                    @enderror
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required autofocus>
                        <label for="floatingInput">Email address</label>
                    </div>
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-floating mb-3 mt-3">
                        <input type="password" class="form-control" id="new_password" name="password" placeholder="Enter New Password" required>
                        <label for="new_password">New Password</label>
                    </div>
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-floating mb-3 mt-3">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                        <label for="password_confirmation">Confirm Password</label>
                    </div>
                    @error('password_confirmation')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn btn-primary w-100 mt-3 mb-3" name="submit">RESET PASSWORD</button>

                </form>
            </div>
        </div>
    </div>
@endsection
