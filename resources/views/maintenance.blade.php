<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - Sistem Presensi PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .maintenance-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .maintenance-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 600px;
            margin: 2rem;
        }
        .maintenance-icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .maintenance-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        .maintenance-subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .progress-bar {
            height: 8px;
            border-radius: 10px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            animation: loading 2s ease-in-out infinite;
        }
        @keyframes loading {
            0%, 100% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
        }
        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin: 2rem 0;
        }
        .contact-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
            border-left: 4px solid #667eea;
        }
        .contact-info h6 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .contact-info p {
            color: #6c757d;
            margin: 0;
            font-size: 0.9rem;
        }
        .admin-login-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            color: #667eea;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .admin-login-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: #667eea;
            text-decoration: none;
        }
        .estimated-time {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin: 1.5rem 0;
        }
        .estimated-time i {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-card">
            <div class="maintenance-icon">
                <i class="fas fa-tools"></i>
            </div>
            
            <h1 class="maintenance-title">Sistem Sedang Maintenance</h1>
            
            <p class="maintenance-subtitle">
                Sistem Presensi PKL WFH sedang dalam tahap pemeliharaan untuk meningkatkan kualitas layanan. 
                Mohon maaf atas ketidaknyamanan yang terjadi.
            </p>

            @if(session('info'))
                <div class="alert alert-info border-0 rounded-3 mb-3" style="background: rgba(23, 162, 184, 0.1); color: #0c5460;">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                </div>
            @endif

            <div class="progress">
                <div class="progress-bar"></div>
            </div>

            <div class="estimated-time">
                <i class="fas fa-clock"></i>
                <strong>Estimasi Selesai:</strong> 
                <span id="estimated-time">Sedang dalam proses...</span>
            </div>

            <div class="mt-4">
                <p class="text-muted small">
                    <i class="fas fa-shield-alt me-1"></i>
                    Data Anda aman dan terlindungi selama proses maintenance
                </p>
            </div>
        </div>
    </div>

    <!-- Admin Login Button -->
    <button class="admin-login-btn" onclick="adminLogin()">
        <i class="fas fa-user-shield me-1"></i>Admin Login
    </button>

    <script>
        function adminLogin() {
            // Redirect langsung ke login dengan force reload
            window.location.href = '/login';
        }
        
        // Auto refresh setiap 5 menit untuk cek apakah maintenance sudah selesai
        setTimeout(function() {
            window.location.reload();
        }, 300000); // 5 menit

        // Update waktu estimasi (optional - bisa disesuaikan)
        function updateEstimatedTime() {
            const now = new Date();
            const estimated = new Date(now.getTime() + (2 * 60 * 60 * 1000)); // +2 jam dari sekarang
            document.getElementById('estimated-time').textContent = 
                estimated.toLocaleString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
        }

        updateEstimatedTime();
    </script>
</body>
</html>
