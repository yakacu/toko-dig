<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Online')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #000428, #004e92); color: #fff; font-family: Arial, sans-serif; }
        .navbar { background-color: rgba(0, 0, 0, 0.6) !important; backdrop-filter: blur(10px); padding: 15px; }
        .navbar-brand { color: #fff !important; font-weight: bold; font-size: 1.5rem; }
        .form-control, .form-select { background: rgba(255, 255, 255, 0.1); border: none; color: white; backdrop-filter: blur(5px); }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.6); }
        .btn-primary { background-color: #ff8b00; border: none; transition: 0.3s; }
        .btn-primary:hover { background-color: #ff3300; }
        .card { background: rgba(255, 255, 255, 0.1); border-radius: 12px; color: white; backdrop-filter: blur(10px); transition: transform 0.3s ease; overflow: hidden; }
        .card:hover { transform: scale(1.05); }
        .card-img-top { border-top-left-radius: 12px; border-top-right-radius: 12px; }
        .card-title { font-weight: bold; }
        .badge { font-size: 0.9rem; }
        .pagination .page-item .page-link { background: rgba(255, 255, 255, 0.2); color: white; border: none; }
        .pagination .page-item.active .page-link { background: #ff8b00; border-radius: 5px; }
    </style>
</head>
<body>
<nav class="navbar fixed-top navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">Toko Online</a>
        <a href="{{ route('produk.search') }}" class="btn btn-primary">Cari Produk</a>
    </div>
</nav>

<br><br><br><br>
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
