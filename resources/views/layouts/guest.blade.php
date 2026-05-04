<!DOCTYPE html>
<html lang="en" class="light-style" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Sistem Inventaris ATK</title>

    {{-- ICON --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- STYLE UTAMA (WAJIB biar ga polos) --}}
    @include('includes.style')

    @stack('css')
</head>

<body style="background:#f5f6fa;">

    {{-- SIMPLE NAVBAR (opsional) --}}
    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <span class="fw-bold">
                Sistem Persediaan ATK
            </span>

            {{-- tombol login --}}
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                Login Admin
            </a>
        </div>
    </nav>

    {{-- CONTENT --}}
    <div class="container">
        @yield('content')
    </div>

    {{-- FOOTER SIMPLE --}}
    <footer class="text-center mt-5 mb-3 text-muted">
        <small>© {{ date('Y') }} Sistem Inventaris</small>
    </footer>

    {{-- SCRIPT --}}
    @include('includes.script')
    @stack('js')

</body>
</html>