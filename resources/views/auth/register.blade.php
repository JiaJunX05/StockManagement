<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Staff Registration</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
</head>

<body>
    <div class="container text-center position-absolute top-50 start-50 translate-middle">
        <div class="register-container">
            <!-- 成功提示框 -->
            @if(session("success"))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session("success") }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 错误提示框 -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 页面标题 -->
            <h1 class="register-title text-center">Staff Registration</h1>

            <!-- 注册表单 -->
            <form action="{{ route('register.submit') }}" method="post">
                @csrf

                <!-- 姓名输入框 -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name"
                           placeholder="Enter Name" required>
                    <label for="name">
                        <i class="bi bi-person me-2"></i>Staff Name
                    </label>
                </div>

                <!-- 邮箱输入框 -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="name@example.com" required>
                    <label for="email">
                        <i class="bi bi-envelope me-2"></i>Email Address
                    </label>
                </div>

                <!-- 密码输入框 -->
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Enter Password" required>
                    <label for="password">
                        <i class="bi bi-lock me-2"></i>Password
                    </label>
                    <i class="bi bi-eye password-toggle position-absolute top-50 end-0 translate-middle-y me-3"
                       onclick="togglePassword('password')"></i>
                </div>

                <!-- 确认密码输入框 -->
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password_confirmation"
                           name="password_confirmation" placeholder="Confirm Password" required>
                    <label for="password_confirmation">
                        <i class="bi bi-shield-lock me-2"></i>Confirm Password
                    </label>
                    <i class="bi bi-eye password-toggle position-absolute top-50 end-0 translate-middle-y me-3"
                       onclick="togglePassword('password_confirmation')"></i>
                </div>

                <!-- 注册按钮 -->
                <button type="submit" class="btn btn-register w-100 mb-3">
                    <i class="bi bi-person-plus me-2"></i>Sign Up
                </button>

                <!-- 返回链接 -->
                <div class="text-center">
                    <a href="{{ route('users') }}" class="back-link">
                        <i class="bi bi-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 密码显示切换功能
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = event.target;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>
</body>
</html>
