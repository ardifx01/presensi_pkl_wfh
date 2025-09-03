<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi PKL SMKN 1 SURABAYA</title>
    <link rel="icon" type="image/png" href="images/smk-negeri-1sby.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex align-items-center mb-3">
        @if(file_exists(public_path('images/smk-negeri-1sby.png')))
            <img src="{{ asset('images/smk-negeri-1sby.png') }}" alt="Logo SMKN 1 Surabaya" style="width: 60px; height: 60px; object-fit: contain;" class="me-3">
        @else
            <div class="d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #FFD700, #FFA500); border: 3px solid #8B4513; border-radius: 8px; font-size: 10px; font-weight: bold; color: #8B4513; text-align: center;">
                SMK<br>NEGERI<br>1 SBY
            </div>
        @endif
        <div>
            <h2 class="mb-0">Presensi PKL SMKN 1 SURABAYA</h2>
            <small class="text-muted">Sistem Presensi Praktek Kerja Lapangan</small>
        </div>
    </div>
    @auth
        <div class="alert alert-secondary d-flex justify-content-between align-items-center py-2 small mb-2">
            <div>
                <strong>{{ auth()->user()->email }}</strong>
                @if(auth()->user()->is_testing)
                    <span class="badge bg-success ms-2">TESTING MODE</span>
                @endif
                @if(auth()->user()->is_admin)
                    <span class="badge bg-warning ms-2">ADMIN</span>
                @endif
                <span class="text-muted ms-2">| <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a></span>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        
        @if(auth()->user()->is_testing)
            <div class="alert alert-success small mb-3">
                <i class="fas fa-flask me-1"></i>
                <strong>MODE TESTING AKTIF:</strong> Anda bisa absen kapan saja tanpa batasan waktu. Sistem akan mencatat waktu sebenarnya untuk testing.
                @if(auth()->user()->is_admin)
                    <br><i class="fas fa-user-shield me-1"></i>
                    <strong>AKSES ADMIN:</strong> Anda juga bisa mengakses <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">dashboard admin</a>.
                @endif
            </div>
        @else
            <div class="alert alert-warning small mb-3">
                Nama, alamat email, dan foto yang terkait dengan Akun Google Anda akan direkam saat Anda mengupload file dan mengirimkan formulir ini.
            </div>
        @endif
    @else
        <div class="alert alert-secondary small mb-3">
            Belum login dengan Google. <a href="{{ route('google.redirect') }}">Login dengan akun Google</a> (opsional, Anda masih bisa isi manual tanpa login).
        </div>
    @endauth
    <p>Silahkan untuk Presensi selama WFH murid PKL Tahun 2025</p>
    <div class="alert alert-info small">
        <strong>Untuk Presensi dilakukan 3X yaitu</strong><br>
        1. <strong>Presensi Pagi</strong> - pk. 09.00 s/d 12.00 WIB<br>
        2. <strong>Presensi Siang</strong> - pk. 13.00 s/d 15.00 WIB<br>
        3. <strong>Presensi Malam</strong> - pk. 16.30 s/d 23.59 WIB<br>
        <br><strong>Penting:</strong> Foto menggunakan Timestamp sesuai dengan sesi dan rentang waktu yang ditentukan!
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data" class="card p-3 shadow-sm">
        @csrf
        <div class="mb-3">
            <label class="form-label">Konsentrasi Keahlian *</label>
            <select name="konsentrasi_keahlian" class="form-select" required>
                <option value="">Pilih</option>
                @foreach([
                    'REKAYASA PERANGKAT LUNAK',
                    'TEKNIK KOMPUTER DAN JARINGAN',
                    'BISNIS DIGITAL',
                    'MANAJEMEN PERKANTORAN',
                    'MANAJEMEN LOGISTIK',
                    'AKUNTANSI',
                    'PERHOTELAN',
                    'DESAIN KOMUNIKASI VISUAL',
                    'PRODUKSI DAN SIARAN PROGRAM TELEVISI'
                ] as $kk)
                    <option value="{{ $kk }}" @selected(old('konsentrasi_keahlian')==$kk)>{{ $kk }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Murid PKL *</label>
            <input type="text" name="nama_murid" class="form-control" value="{{ old('nama_murid', auth()->user()->name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kelas (contoh: 12 RPL 1) *</label>
            <input type="text" name="kelas" class="form-control" value="{{ old('kelas') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Perusahaan / Instansi PKL *</label>
            <input type="text" name="nama_perusahaan" class="form-control" value="{{ old('nama_perusahaan') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat Perusahaan / Instansi PKL *</label>
            <input type="text" name="alamat_perusahaan" class="form-control" value="{{ old('alamat_perusahaan') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Pembimbing Sekolah *</label>
            <input type="text" name="nama_pembimbing_sekolah" class="form-control" value="{{ old('nama_pembimbing_sekolah') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Pembimbing DUDIKA *</label>
            <input type="text" name="nama_pembimbing_dudika" class="form-control" value="{{ old('nama_pembimbing_dudika') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Sesi Presensi *</label>
            <select name="sesi_presensi" class="form-select" required>
                <option value="">Pilih Sesi</option>
                @foreach(['Pagi (09.00-12.00 WIB)','Siang (13.00-15.00 WIB)','Malam (16.30-23.59 WIB)'] as $s)
                    <option value="{{ $s }}" @selected(old('sesi_presensi')==$s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload Foto Murid PKL (boleh selfi) dilengkapi dengan Timestamp * (format gambar)</label>
            <input type="file" name="foto_murid" class="form-control" accept="image/*" required id="foto_murid">
            <small class="text-muted">Upload 1 file yang didukung: image. Maks 2 MB.</small>
            <div class="invalid-feedback" id="file-error" style="display: none;"></div>
        </div>
        <div class="d-grid">
            <button class="btn btn-primary" type="submit">Kirim Presensi</button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('absensi.index') }}" class="small">Lihat Data Presensi</a>
    <div class="small text-muted mt-2">User Support: <a href="mailto:humas@smkn1-sby.sch.id">humas@smkn1-sby.sch.id</a></div>
    </div>
</div>

<script>
document.getElementById('foto_murid').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const errorDiv = document.getElementById('file-error');
    const maxSize = 2 * 1024 * 1024; // 2 MB in bytes
    
    if (file) {
        if (file.size > maxSize) {
            errorDiv.textContent = 'Ukuran file terlalu besar! Maksimal 2 MB.';
            errorDiv.style.display = 'block';
            e.target.setCustomValidity('File terlalu besar');
            e.target.classList.add('is-invalid');
        } else {
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
            e.target.setCustomValidity('');
            e.target.classList.remove('is-invalid');
        }
    }
});
</script>
</body>
</html>
