{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- JQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.1.0/progressbar.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    @yield("css")
    <title>@yield("title") || StockManagement</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <header>
        @include("admin.layouts.header")
    </header>

    <main class="flex-grow-1">
        @yield("content")
    </main>

    <footer>
        @include("admin.layouts.footer")
    </footer>

    @yield("scripts")
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title") || StockManagement</title>

    <!-- ==================== CSS Section ==================== -->
    <!-- Bootstrap 5.3.3 Core CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    @yield("css")

    <!-- ==================== JS Section ==================== -->
    <!-- jQuery 3.7.1 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"
            defer></script>

    <!-- Bootstrap 5.3.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"
            defer></script>

    <!-- ProgressBar.js 1.1.0 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.1.0/progressbar.min.js"
            integrity="sha512-EZxkWrYMdYa3H/LDpF2ZaIXX/DEkP+IYed/PtV/GQ2kvwbKwSzwc/4MxkGXe4N3QDuKxDnVkNSphcO2LZ6hqw=="
            crossorigin="anonymous"
            defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.1.0/progressbar.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>

<body class="d-flex flex-column min-vh-100">
    <!-- ==================== Header ==================== -->
    <header>
        @include("admin.layouts.header")
    </header>

    <!-- ==================== Main Content ==================== -->
    <main class="flex-grow-1">
        @yield("content")
    </main>

    <!-- ==================== Footer ==================== -->
    <footer>
        @include("admin.layouts.footer")
    </footer>

    <!-- ==================== Scripts ==================== -->
    <!-- App Core JS -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Custom Page Scripts -->
    @yield("scripts")
</body>
</html>
