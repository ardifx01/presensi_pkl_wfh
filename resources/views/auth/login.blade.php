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
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            max-width: 400px;
        }
        .notification {
            margin-bottom: 10px;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }
        .notification.show {
            opacity: 1;
            transform: translateX(0);
        }
        .notification.hide {
            opacity: 0;
            transform: translateX(100%);
        }
        .btn-loading {
            position: relative;
        }
        .btn-loading .spinner {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .btn-loading .btn-text {
            opacity: 0;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">

<!-- Notification Container -->
<div class="notification-container" id="notificationContainer"></div>

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

                    <!-- Inline notifications (fallback for no JS) -->
                    <div id="inlineNotifications">
                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('dev_login_url'))
                            <div class="alert alert-warning alert-dismissible fade show small py-2" role="alert">
                                <i class="fas fa-code me-2"></i>Debug (dev): <a href="{{ session('dev_login_url') }}" class="alert-link">{{ session('dev_login_url') }}</a>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('login.email.send') }}" class="mb-3" id="emailForm">
                        @csrf
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" name="email" placeholder="Email" required value="{{ old('email') }}" id="emailInput">
                            </div>
                        </div>
                        <button class="btn btn-primary w-100" type="submit" id="emailBtn">
                            <span class="btn-text">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Link Login
                            </span>
                            <span class="spinner d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                        <div class="d-flex justify-content-between mt-2">
                            <a href="{{ route('login.reset.cooldown') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-redo me-1"></i>Reset Cooldown
                            </a>
                            <a href="{{ route('login.email.send') }}?force=1" onclick="event.preventDefault(); document.getElementById('emailForm').action='{{ route('login.email.send') }}?force=1'; document.getElementById('emailForm').submit();" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-bolt me-1"></i>Kirim Paksa
                            </a>
                        </div>
                    </form>

                    <div class="small text-muted mb-3 text-center">
                        <i class="fas fa-info-circle me-1"></i>
                        Tidak menerima email? Tunggu ±1 menit, cek folder Spam/Junk, atau kirim ulang.
                    </div>

                    <hr class="my-3">
                    
                    <div class="text-center small text-muted mb-3">
                        <i class="fas fa-user-shield me-1"></i>Login Admin
                    </div>
                    <form action="{{ route('admin.login') }}" method="POST" id="adminForm">
                        @csrf
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="username" placeholder="Username Admin" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="password" placeholder="Password Admin" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100" id="adminBtn">
                            <span class="btn-text">
                                <i class="fas fa-sign-in-alt me-2"></i>Login Admin
                            </span>
                            <span class="spinner d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Move server notifications to floating container
    const inlineNotifications = document.getElementById('inlineNotifications');
    const notificationContainer = document.getElementById('notificationContainer');
    
    // Move existing alerts to floating position
    const alerts = inlineNotifications.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.classList.add('notification');
        notificationContainer.appendChild(alert);
        setTimeout(() => alert.classList.add('show'), 100);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            alert.classList.add('hide');
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Clear inline container
    inlineNotifications.innerHTML = '';
    
    // Form submission handlers with loading states
    const emailForm = document.getElementById('emailForm');
    const emailBtn = document.getElementById('emailBtn');
    const adminForm = document.getElementById('adminForm');
    const adminBtn = document.getElementById('adminBtn');
    
    function setLoading(btn, loading) {
        const btnText = btn.querySelector('.btn-text');
        const spinner = btn.querySelector('.spinner');
        
        if (loading) {
            btn.classList.add('btn-loading');
            btn.disabled = true;
            btnText.style.opacity = '0';
            spinner.classList.remove('d-none');
        } else {
            btn.classList.remove('btn-loading');
            btn.disabled = false;
            btnText.style.opacity = '1';
            spinner.classList.add('d-none');
        }
    }
    
    function showNotification(message, type = 'success', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible notification py-2`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" onclick="this.parentElement.classList.add('hide'); setTimeout(() => this.parentElement.remove(), 300)"></button>
        `;
        
        notificationContainer.appendChild(notification);
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Auto-hide
        setTimeout(() => {
            notification.classList.add('hide');
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }
    
    emailForm.addEventListener('submit', function(e) {
        setLoading(emailBtn, true);
        showNotification('Mengirim link login...', 'info', 2000);
    });
    
    adminForm.addEventListener('submit', function(e) {
        setLoading(adminBtn, true);
    });
    
    // Email validation feedback
    const emailInput = document.getElementById('emailInput');
    emailInput.addEventListener('input', function() {
        if (this.value && !this.checkValidity()) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
});
</script>
</body>
</html>
