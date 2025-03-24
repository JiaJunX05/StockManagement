<!DOCTYPE html>
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
    <div class="d-flex flex-grow-1">
        <aside class="d-flex flex-column" style="min-height: 100vh; background: linear-gradient(180deg, #2c3e50, #34495e);">
            @include("staff.layouts.sidebar")
        </aside>

        <div class="d-flex flex-column flex-grow-1">
            <header>
                @include("staff.layouts.header")
            </header>

            <main class="flex-grow-1 p-4">
                @yield("content")
            </main>

            <footer>
                @include("staff.layouts.footer")
            </footer>
        </div>
    </div>

    @yield("scripts")
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
