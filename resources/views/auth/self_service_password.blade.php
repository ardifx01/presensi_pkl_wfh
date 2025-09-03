<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password Mandiri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-2">Ganti Password Mandiri</h5>
                    <p class="text-muted small mb-3">Gunakan fitur ini jika Anda masih ingat password lama tetapi ingin menggantinya. Jika lupa password, hubungi admin.</p>
                    @if(session('success'))
                        <div class="alert alert-success py-2">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger py-2 mb-3">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.self.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small">Email Terdaftar</label>
                            <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Password Lama</label>
                            <input type="password" name="current_password" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Password Baru</label>
                            <input type="password" name="password" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-sm" required>
                        </div>
                        <button class="btn btn-primary w-100 btn-sm">Simpan Password Baru</button>
                        <a href="{{ route('login') }}" class="btn btn-link w-100 btn-sm mt-2">Kembali ke Login</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>