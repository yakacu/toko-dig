<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff; font-family: 'Arial', sans-serif; min-height: 100vh; }
        .container { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 30px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); }
        .form-control, .form-select { background: rgba(255, 255, 255, 0.2); color: #fff; border: 1px solid rgba(255, 255, 255, 0.3); }
        .form-control:focus, .form-select:focus { background: rgba(255, 255, 255, 0.3); border-color: #ff8c00; box-shadow: 0 0 5px #ff8c00; color: #fff; }
        .btn-primary { background-color: #ff8c00; border: none; }
        .btn-primary:hover { background-color: #e07b00; }
        .btn-secondary { background-color: rgba(255, 255, 255, 0.3); border: none; }
        .btn-secondary:hover { background-color: rgba(255, 255, 255, 0.5); }
        img { display: block; margin-top: 10px; border-radius: 8px; }
        .badge { font-size: 0.9em; padding: 5px 10px; border-radius: 8px; }
        .invalid-feedback { display: block; }
    </style>
</head>
<body>
<div class="container mt-5" style="max-width: 900px;">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
