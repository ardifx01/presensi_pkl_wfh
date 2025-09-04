<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi PKL - Login</title>
    <link rel="icon" type="image/png" href="images/smk-negeri-1sby.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FFD700, #FFA500, #FF6B35);
        }
        .login-header {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
            padding: 3rem 2rem 2rem;
            text-align: center;
            position: relative;
        }
        .school-logo {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            border: 4px solid white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            font-size: 11px;
            font-weight: bold;
            color: #1565C0;
            text-align: center;
            line-height: 1.1;
        }
        .app-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .app-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 400;
        }
        .login-body {
            padding: 2.5rem 2rem;
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .input-group {
            margin-bottom: 1rem;
        }
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            color: #6c757d;
            border-radius: 12px 0 0 12px;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-left: none;
            border-radius: 0 12px 12px 0;
            padding: 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #2196F3;
            box-shadow: 0 0 0 0.2rem rgba(33,150,243,0.25);
        }
        .form-control:focus + .input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: #2196F3;
        }
        .btn-toggle-password {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-left: none;
            border-radius: 0 12px 12px 0;
            color: #6c757d;
            padding: 0 15px;
        }
        .btn-toggle-password:hover {
            background: #e9ecef;
            color: #495057;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1976D2, #1565C0);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(33,150,243,0.3);
        }
        .form-check-label {
            color: #6c757d;
            font-size: 14px;
        }
        .register-link {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }
        .register-link a {
            color: #2196F3;
            text-decoration: none;
            font-weight: 600;
        }
        .register-link a:hover {
            color: #1976D2;
            text-decoration: underline;
        }
        .dev-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            font-size: 12px;
            color: #6c757d;
        }
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }
        .alert-danger {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            @if(file_exists(public_path('images/smk-negeri-1sby.png')))
                <img src="{{ asset('images/smk-negeri-1sby.png') }}" alt="Logo SMKN 1 Surabaya" 
                     style="width: 90px; height: 90px; object-fit: contain; background: white; border-radius: 20px; padding: 10px; margin-bottom: 1.5rem; box-shadow: 0 8px 20px rgba(0,0,0,0.2);">
            @else
                <div class="school-logo">
                    SMK<br>NEGERI<br>1<br>SBY
                </div>
            @endif
            <h1 class="app-title">Presensi PKL WFH</h1>
            <p class="app-subtitle">SMKN 1 Surabaya</p>
        </div>

        <div class="login-body">
            <!-- Informasi Penting untuk Siswa -->
            <div class="alert alert-info alert-dismissible fade show" role="alert" style="background: linear-gradient(135deg, #17a2b8, #0c7b8a); color: white; border: none;">
                <h6 class="alert-heading mb-2">
                    <i class="fas fa-info-circle me-2"></i>Informasi Penting untuk Siswa
                </h6>
                <small>
                    <strong>📚 Siswa Baru:</strong> Silakan <a href="{{ route('register') }}" class="text-white text-decoration-underline"><strong>daftar akun terlebih dahulu</strong></a> sebelum melakukan presensi.<br>
                    <strong>📊 Data Absensi:</strong> Semua riwayat presensi Anda akan tersimpan berdasarkan email yang terdaftar.<br>
                    <strong>🔐 Keamanan:</strong> Pastikan menggunakan password yang kuat dan mudah diingat.
                </small>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>Email
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" id="email" placeholder="Masukkan email Anda" 
                               value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" id="password" placeholder="Masukkan password Anda" required>
                        <button class="btn btn-toggle-password" type="button" id="togglePassword">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            <i class="fas fa-heart me-1"></i>Ingat saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk Sekarang
                </button>
            </form>

            <!-- Register Link -->
            <div class="register-link">
                <div class="text-center mb-2">
                    <p class="mb-1">
                        <i class="fas fa-user-plus me-2"></i>Belum punya akun? 
                        <a href="{{ route('register') }}"><strong>Daftar di sini</strong></a>
                    </p>
                    @auth
                        <p class="mb-1">
                            <i class="fas fa-key me-2"></i>
                            <a href="{{ route('password.change.form') }}" class="text-decoration-none">Ganti Password</a>
                        </p>
                    @else
                        <p class="mb-1 small text-muted">
                            <i class="fas fa-key me-1"></i> Password sementara untuk Murid yang melaksanakan PKL adalah <strong>defaultpass123</strong>.
                        </p>
                        <p class="mb-0 small">
                            <i class="fas fa-unlock-alt me-1"></i>
                            <a href="{{ route('password.self.form') }}" class="text-decoration-none">Ganti password sekarang</a>
                        </p>
                    @endauth
                    <small class="text-muted">
                        <i class="fas fa-graduation-cap me-1"></i>Siswa baru wajib mendaftar untuk menyimpan riwayat presensi
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility with smooth animation
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            this.setAttribute('title', 'Sembunyikan password');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            this.setAttribute('title', 'Tampilkan password');
        }
    });

    // Auto-dismiss alerts after 5 seconds with fade out
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (alert.querySelector('.btn-close')) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }
        });
    }, 5000);

    // Form validation feedback
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim() !== '') {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });

    // Add loading state to submit button
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Masuk...';
        submitBtn.disabled = true;
        
        // Re-enable button after 3 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });

    // Add subtle animations on page load
    document.addEventListener('DOMContentLoaded', function() {
        const card = document.querySelector('.login-card');
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);
    });

    // Add tooltips for better UX
    document.getElementById('togglePassword').setAttribute('title', 'Tampilkan password');
    document.getElementById('remember').setAttribute('title', 'Ingat saya di perangkat ini');
</script>

</body>
</html>
