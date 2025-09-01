<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluasi Tengah Semester Murid PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-2">Evaluasi Tengah Semester Murid PKL</h2>
    @auth
        <div class="alert alert-secondary d-flex justify-content-between align-items-center py-2 small mb-2">
            <div>
                <strong>{{ auth()->user()->email }}</strong>
                <span class="text-muted ms-2">| <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Ganti akun</a></span>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        <div class="alert alert-warning small mb-3">
            Nama, alamat email, dan foto yang terkait dengan Akun Google Anda akan direkam saat Anda mengupload file dan mengirimkan formulir ini.
        </div>
    @else
        <div class="alert alert-secondary small mb-3">
            Belum login dengan Google. <a href="{{ route('google.redirect') }}">Login dengan akun Google</a> (opsional, Anda masih bisa isi manual tanpa login).
        </div>
    @endauth
    <p>Silahkan untuk Presensi selama WFH murid PKL Tahun 2025</p>
    <div class="alert alert-info small">
        <strong>Untuk Presensi dilakukan 3X yaitu</strong><br>
        1. pk. 10.00 WIB (Pagi)<br>
        2. pk. 14.00 WIB (Siang)<br>
        3. pk. 16.30 WIB (Sore)<br>
        <br>Foto menggunakan Timestamp sesuai dengan sesi tersebut ya
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
                @foreach(['10.00 WIB (Pagi)','14.00 WIB (Siang)','16.30 WIB (Sore)'] as $s)
                    <option value="{{ $s }}" @selected(old('sesi_presensi')==$s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload Foto Murid PKL (boleh selfi) dilengkapi dengan Timestamp * (format gambar)</label>
            <input type="file" name="foto_murid" class="form-control" accept="image/*" required>
            <small class="text-muted">Upload 1 file yang didukung: image. Maks 10 MB.</small>
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
</body>
</html>
