<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Use CDN to link Bootstrap and Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
    <title>User Register Form</title>
</head>
<body>
    <div class="container text-center position-absolute top-50 start-50 translate-middle">
        <!-- Success Alert -->
            @if(session("success"))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session("success") }}
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

        <div class="row justify-content-center">
            <h1 class="pb-2 mt-4 mb-4 text-primary-emphasis border-bottom border-danger text-center" style="font-weight: 700;">Staff Registration</h1>

            <form action="{{ route('register.submit') }}" method="post">
                @csrf

                <div class="form-floating mb-3 mt-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                    <label for="name">Staff Name</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required>
                    <label for="floatingInput">Email Address</label>
                </div>

                <div class="form-floating mb-3 mt-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                    <label for="password">Password</label>
                </div>

                <div class="form-floating mb-3 mt-3">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                    <label for="password_confirmation">Confirm Password</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3" name="submit">SIGN UP</button>

                <div class="col mt-3">
                    <p class="text-secondary" style="font-size: 20px;">
                        <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: red;">Back To Dashboard</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
