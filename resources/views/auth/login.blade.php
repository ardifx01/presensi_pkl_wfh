<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Presensi PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3 text-center">Presensi PKL - Login</h4>
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <a class="btn btn-danger w-100 mb-3" href="{{ route('google.redirect') }}">Login dengan Google</a>
                    <div class="text-center small text-muted mb-2">atau isi manual tanpa login</div>
                    <a class="btn btn-outline-primary w-100" href="{{ route('absensi.create') }}">Langsung ke Form Presensi</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
