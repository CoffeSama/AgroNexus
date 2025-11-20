<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'AgroNexus | Acceso')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap + AdminLTE + FontAwesome (mismos CDNs que el layout principal) --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        body {
            background: #f8f9fc;
        }
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="auth-wrapper">
    <div class="card auth-card shadow">
        <div class="card-header text-center" style="background:#2c5530; color:white;">
            <img src="{{ asset('images/logo.png') }}" alt="AgroNexus" class="mb-2" style="height:40px;">
            <h4 class="mb-0">@yield('card_title', 'AgroNexus')</h4>
        </div>
        <div class="card-body">
            @yield('content')
        </div>
        <div class="card-footer text-center text-muted">
            &copy; {{ date('Y') }} AgroNexus
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>