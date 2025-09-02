<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi PKL - Login</title>
    <link rel="icon" type="image/png" href="images/logo-smkn1-sby.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Header dengan Logo -->
                    <div class="text-center mb-4">
                        @if(file_exists(public_path('images/smk-negeri-1sby.png')))
                            <img src="{{ asset('images/smk-negeri-1sby.png') }}" alt="Logo SMKN 1 Surabaya" style="width: 80px; height: 80px; object-fit: contain;" class="mb-3">
                        @else
                            <div class="d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #FFD700, #FFA500); border: 3px solid #8B4513; border-radius: 12px; font-size: 12px; font-weight: bold; color: #8B4513; text-align: center;">
                                SMK<br>NEGERI<br>1<br>SBY
                            </div>
                        @endif
                        <h4 class="mb-1">Presensi PKL</h4>
                        <p class="text-muted small mb-0">SMKN 1 Surabaya</p>
                    </div>
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <a class="btn btn-danger w-100 mb-3" href="{{ route('google.redirect') }}">
                        Login dengan Google
                    </a>
                    <div class="text-center small text-muted mb-2">atau Login untuk Admin</div>
                    
                    <form action="{{ route('admin.login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" name="username" placeholder="Username Admin" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password Admin" required>
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100">Login Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
