<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LTMNM - Đồ án Nhóm 6')</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body class="bg-light">
    {{-- header --}}
    @include('layouts.header')
    {{-- content --}}

    @yield('content')
    {{-- footer --}}
    @include('layouts.footer')

    {{-- Bootstrap 5 JS --}}
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
