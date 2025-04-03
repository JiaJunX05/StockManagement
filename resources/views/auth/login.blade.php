<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Login Form</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/auth/login.css') }}">
</head>

<body>
    <!-- 背景视频 -->
    <div class="video-container">
        <div class="video-overlay"></div>
        <video autoplay loop muted playsinline class="bg-video">
            <source src="{{ asset('assets/backgrounds/bg-001.mp4') }}" type="video/mp4">
        </video>
    </div>

    <!-- 主容器 -->
    <div class="container text-center position-absolute top-50 start-50 translate-middle">
        <!-- 提示信息 -->
        @if(session("success"))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <span>{{ session("success") }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- 登录表单 -->
        <div class="row justify-content-center">
            <!-- 标题 -->
            <h1 class="pb-2 mt-4 mb-3 text-danger border-bottom border-danger text-center fw-bold">Sign In</h1>

            <form action="{{route('login.submit')}}" method="post">
                @csrf
                <!-- 邮箱输入框 -->
                <div class="form-group mb-3 mt-3">
                    <label for="email" class="form-label text-white text-start d-block">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-envelope text-white"></i></span>
                        <input type="email" class="form-control border-start-0 bg-transparent text-white" id="email" name="email" required>
                    </div>
                </div>

                <!-- 密码输入框 -->
                <div class="form-group mb-3 mt-3">
                    <label for="password" class="form-label text-white text-start d-block">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-lock text-white"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0 bg-transparent text-white" id="password" name="password" required autocomplete="off">
                        <span class="input-group-text bg-transparent border-start-0" role="button"onclick="togglePassword()"><i class="bi bi-eye-slash text-white" id="togglePassword"></i></span>
                    </div>
                </div>

                <!-- 记住我和忘记密码 -->
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label text-white" for="remember">Remember Me</label>
                    </div>
                    <a href="#" class="text-decoration-none text-danger">Forgot Password?</a>
                </div>

                <!-- 登录按钮 -->
                <button type="submit" class="btn btn-danger w-100 mb-3 rounded-pill" name="submit">Log In <i class="bi bi-box-arrow-in-right ms-2"></i></button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 密码显示切换
        function togglePassword() {
            const password = document.getElementById('password');
            const toggle = document.getElementById('togglePassword');

            if (password.type === 'password') {
                password.type = 'text';
                toggle.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                password.type = 'password';
                toggle.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }

        // 视频自动播放
        document.addEventListener("DOMContentLoaded", function () {
            let video = document.querySelector(".bg-video");
            video.play().catch(error => console.log("自动播放被阻止", error));
        });

        // 输入动画效果
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function(e) {
                if (this.value) {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
        });

        // 按钮点击效果
        document.querySelector('.btn-danger').addEventListener('click', function(e) {
            let x = e.clientX - e.target.offsetLeft;
            let y = e.clientY - e.target.offsetTop;

            let ripple = document.createElement('span');
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    </script>
</body>
</html>
