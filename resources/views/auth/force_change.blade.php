<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Ganti Password</h5>
                        <p class="text-muted small">Anda menggunakan password sementara. Silakan buat password baru untuk melanjutkan.</p>
                        @if(session('status'))
                            <div class="alert alert-success py-2">{{ session('status') }}</div>
                        @endif
                        <form method="POST" action="{{ route('password.change.update') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autofocus>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <button class="btn btn-primary w-100">Simpan Password</button>
                        </form>
                        <form method="POST" action="{{ route('logout') }}" class="mt-3 text-center">
                            @csrf
                            <button class="btn btn-link text-danger p-0 small">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
