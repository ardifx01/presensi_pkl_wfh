<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi PKL - Daftar</title>
    <link rel="icon" type="image/png" href="images/smk-negeri-1sby.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            max-width: 480px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }
        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FFD700, #FFA500, #28a745);
        }
        .register-header {
            background: linear-gradient(135deg, #28a745, #20c997);
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
        .register-body {
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
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40,167,69,0.25);
        }
        .form-control:focus + .input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: #28a745;
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
        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40,167,69,0.3);
        }
        .login-link {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }
        .login-link a {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            color: #20c997;
            text-decoration: underline;
        }
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        .alert-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        .password-requirements {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 2px;
        }
        .requirement.valid {
            color: #28a745;
        }
        .requirement.invalid {
            color: #dc3545;
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            @if(file_exists(public_path('images/smk-negeri-1sby.png')))
                <img src="{{ asset('images/smk-negeri-1sby.png') }}" alt="Logo SMKN 1 Surabaya" 
                     style="width: 90px; height: 90px; object-fit: contain; background: white; border-radius: 20px; padding: 10px; margin-bottom: 1.5rem; box-shadow: 0 8px 20px rgba(0,0,0,0.2);">
            @else
                <div class="school-logo">
                    SMK<br>NEGERI<br>1<br>SBY
                </div>
            @endif
            <h1 class="app-title">Daftar Akun</h1>
            <p class="app-subtitle">Presensi PKL - SMKN 1 Surabaya</p>
        </div>

        <div class="register-body">
            <!-- Informasi Penting untuk Siswa -->
            <div class="alert alert-info alert-dismissible fade show" role="alert" style="background: linear-gradient(135deg, #17a2b8, #0c7b8a); color: white; border: none;">
                <h6 class="alert-heading mb-2">
                    <i class="fas fa-info-circle me-2"></i>Penting untuk Siswa PKL
                </h6>
                <small>
                    <strong>📧 Email:</strong> Akan digunakan sebagai identitas utama untuk menyimpan semua data presensi Anda.<br>
                    <strong>🔐 Password:</strong> Gunakan password yang kuat namun mudah diingat untuk keamanan akun.<br>
                    <strong>📊 Data:</strong> Setelah registrasi, semua riwayat absensi akan tersimpan berdasarkan email Anda.
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

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="fas fa-user me-2"></i>Nama Lengkap
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user-circle"></i>
                        </span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" id="name" placeholder="Masukkan nama lengkap Anda" 
                               value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>Email
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-at"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" id="email" placeholder="contoh@email.com" 
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
                               name="password" id="password" placeholder="Minimal 8 karakter" required>
                        <button class="btn btn-toggle-password" type="button" id="togglePassword">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div class="password-requirements" id="passwordRequirements">
                        <div class="requirement" id="lengthReq">
                            <i class="fas fa-times me-2"></i>Minimal 8 karakter
                        </div>
                        <div class="requirement" id="letterReq">
                            <i class="fas fa-times me-2"></i>Mengandung huruf
                        </div>
                        <div class="requirement" id="numberReq">
                            <i class="fas fa-times me-2"></i>Mengandung angka
                        </div>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock me-2"></i>Konfirmasi Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               name="password_confirmation" id="password_confirmation" placeholder="Ulangi password Anda" required>
                        <button class="btn btn-toggle-password" type="button" id="togglePasswordConfirm">
                            <i class="fas fa-eye" id="toggleIconConfirm"></i>
                        </button>
                    </div>
                    <div id="passwordMatch" class="password-requirements"></div>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success w-100 mb-3">
                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                </button>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                <p class="mb-0">
                    <i class="fas fa-sign-in-alt me-2"></i>Sudah punya akun? 
                    <a href="{{ route('login') }}">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility for both password fields
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

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        const passwordConfirm = document.getElementById('password_confirmation');
        const icon = document.getElementById('toggleIconConfirm');
        
        if (passwordConfirm.type === 'password') {
            passwordConfirm.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            this.setAttribute('title', 'Sembunyikan password');
        } else {
            passwordConfirm.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            this.setAttribute('title', 'Tampilkan password');
        }
    });

    // Password strength validation
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const lengthReq = document.getElementById('lengthReq');
        const letterReq = document.getElementById('letterReq');
        const numberReq = document.getElementById('numberReq');

        // Check length
        if (password.length >= 8) {
            lengthReq.classList.remove('invalid');
            lengthReq.classList.add('valid');
            lengthReq.querySelector('i').classList.remove('fa-times');
            lengthReq.querySelector('i').classList.add('fa-check');
        } else {
            lengthReq.classList.remove('valid');
            lengthReq.classList.add('invalid');
            lengthReq.querySelector('i').classList.remove('fa-check');
            lengthReq.querySelector('i').classList.add('fa-times');
        }

        // Check letter
        if (/[a-zA-Z]/.test(password)) {
            letterReq.classList.remove('invalid');
            letterReq.classList.add('valid');
            letterReq.querySelector('i').classList.remove('fa-times');
            letterReq.querySelector('i').classList.add('fa-check');
        } else {
            letterReq.classList.remove('valid');
            letterReq.classList.add('invalid');
            letterReq.querySelector('i').classList.remove('fa-check');
            letterReq.querySelector('i').classList.add('fa-times');
        }

        // Check number
        if (/\d/.test(password)) {
            numberReq.classList.remove('invalid');
            numberReq.classList.add('valid');
            numberReq.querySelector('i').classList.remove('fa-times');
            numberReq.querySelector('i').classList.add('fa-check');
        } else {
            numberReq.classList.remove('valid');
            numberReq.classList.add('invalid');
            numberReq.querySelector('i').classList.remove('fa-check');
            numberReq.querySelector('i').classList.add('fa-times');
        }

        // Check password confirmation match
        checkPasswordMatch();
    });

    // Password confirmation matching
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;
        const matchDiv = document.getElementById('passwordMatch');

        if (passwordConfirm.length > 0) {
            if (password === passwordConfirm) {
                matchDiv.innerHTML = '<div class="requirement valid"><i class="fas fa-check me-2"></i>Password cocok</div>';
            } else {
                matchDiv.innerHTML = '<div class="requirement invalid"><i class="fas fa-times me-2"></i>Password tidak cocok</div>';
            }
        } else {
            matchDiv.innerHTML = '';
        }
    }

    document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

    // Auto-dismiss alerts after 5 seconds
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
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mendaftar...';
        submitBtn.disabled = true;
        
        // Re-enable button after 3 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });

    // Add subtle animations on page load
    document.addEventListener('DOMContentLoaded', function() {
        const card = document.querySelector('.register-card');
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);
    });

    // Add tooltips
    document.getElementById('togglePassword').setAttribute('title', 'Tampilkan password');
    document.getElementById('togglePasswordConfirm').setAttribute('title', 'Tampilkan password');
</script>

</body>
</html>
