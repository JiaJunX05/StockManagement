@extends("admin.layouts.app")

@section("title", "Email Varification")
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

                <h1 class="pb-2 mb-4 text-primary-emphasis border-bottom border-danger" style="font-weight: 700;">Email Varification</h1>
                <form action="{{ route('password.email') }}" method="post">
                    @csrf
                    <p class="bg-primary-subtle text-danger p-3 rounded mb-3" style="font-size: 16px; margin-bottom: 20px;">
                        Please enter your email address to verify your account. <br>
                        We'll send a verification email with a link. <br>
                        Please check your inbox and click on the link to verify your account or reset your password.
                    </p>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required autofocus>
                        <label for="floatingInput">Email address</label>
                    </div>
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn btn-primary w-100 mt-3 mb-3" name="submit">EMAIL VARIFICATION</button>

                </form>
            </div>
        </div>
    </div>
@endsection
