<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Presensi PKL</title>
    <link rel="icon" type="image/png" href="images/smk-negeri-1sby.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-admin {
            background-color: #0d6efd;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        .navbar-admin img {
            height: 60px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
        .navbar-admin img[alt] {
            
            border-radius: 8px;
            padding: 8px;
        }
        .filter-section {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .stats-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
        }
        .table-container {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        
        /* Custom Pagination Styling */
        .pagination {
            margin: 0;
            font-size: 0.8rem;
        }
        .pagination-sm .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border-radius: 0.25rem;
            margin: 0 1px;
            min-width: 32px;
            text-align: center;
        }
        .pagination-sm .page-item:first-child .page-link,
        .pagination-sm .page-item:last-child .page-link {
            border-radius: 0.25rem;
        }
        .pagination-sm .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        .pagination-sm .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        .pagination-sm .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }
        
        /* Responsive table improvements */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            .pagination-sm .page-link {
                padding: 0.2rem 0.4rem;
                font-size: 0.75rem;
                min-width: 28px;
            }
            .filter-section .col-md-2,
            .filter-section .col-md-4 {
                margin-bottom: 0.5rem;
            }
            .d-flex.gap-2 {
                gap: 0.5rem !important;
            }
        }
        
        @media (max-width: 576px) {
            .pagination-sm .page-link {
                padding: 0.15rem 0.3rem;
                font-size: 0.7rem;
                min-width: 24px;
            }
            .table-container {
                margin: 0 -15px;
                border-radius: 0;
            }
            .navbar-admin img {
                height: 45px;
            }
            .navbar-admin h3 {
                font-size: 1.2rem;
            }
            .navbar-admin p {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <div class="navbar-admin">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    @if(file_exists(public_path('images/smk-negeri-1sby.png')))
                        <img src="{{ asset('images/smk-negeri-1sby.png') }}" alt="Logo SMKN 1 Surabaya" style="width: 60px; height: 60px; object-fit: contain;" class="me-3">
                    @else
                        <div class="d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 10px; font-weight: bold; color: white; text-align: center;">
                            SMK<br>NEGERI<br>1 SBY
                        </div>
                    @endif
                    <div>
                        <h3 class="mb-1 text-white">Dashboard Admin Presensi PKL</h3>
                        <p class="mb-0 text-white-50">Kelola data presensi siswa PKL SMKN 1 Surabaya</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <!-- Maintenance Toggle Button -->
                    <div class="btn-group me-3">
                        @php
                            $maintenanceActive = file_exists(storage_path('framework/maintenance'));
                        @endphp
                        @if($maintenanceActive)
                            <button type="button" class="btn btn-warning btn-sm" onclick="toggleMaintenance('up')">
                                <i class="fas fa-tools me-1"></i>Matikan Maintenance
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="toggleMaintenance('down')">
                                <i class="fas fa-tools me-1"></i>Aktifkan Maintenance
                            </button>
                        @endif
                    </div>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf 
                        <button class="btn btn-outline-light">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="mb-0">Filter Data Presensi</h5>
                @if(isset($isDaily) && $isDaily)
                    <span class="badge bg-info text-dark" title="Menampilkan data presensi hari ini secara otomatis sampai Anda memilih tanggal.">
                        Mode Harian Otomatis ({{ now()->format('d M Y') }})
                    </span>
                @elseif(request('tanggal'))
                    <span class="badge bg-secondary" title="Data difilter berdasarkan tanggal yang dipilih.">
                        Tanggal: {{ request('tanggal') }}
                    </span>
                @endif
            </div>
            <form class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sesi Presensi</label>
                    <select name="sesi" class="form-select">
                        <option value="">Semua Sesi</option>
                        @foreach(['Pagi (09.00-12.00 WIB)','Siang (13.00-15.00 WIB)','Malam (16.30-23.59 WIB)'] as $s)
                            <option value="{{ $s }}" @selected(request('sesi')==$s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kelas</label>
                    <input type="text" name="kelas" value="{{ request('kelas') }}" class="form-control" placeholder="12 RPL 1">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Konsentrasi Keahlian</label>
                    <select name="konsentrasi" class="form-select">
                        <option value="">Semua Konsentrasi</option>
                        <option value="REKAYASA PERANGKAT LUNAK" @selected(request('konsentrasi')=='REKAYASA PERANGKAT LUNAK')>REKAYASA PERANGKAT LUNAK</option>
                        <option value="TEKNIK KOMPUTER DAN JARINGAN" @selected(request('konsentrasi')=='TEKNIK KOMPUTER DAN JARINGAN')>TEKNIK KOMPUTER DAN JARINGAN</option>
                        <option value="BISNIS DIGITAL" @selected(request('konsentrasi')=='BISNIS DIGITAL')>BISNIS DIGITAL</option>
                        <option value="MANAJEMEN PERKANTORAN" @selected(request('konsentrasi')=='MANAJEMEN PERKANTORAN')>MANAJEMEN PERKANTORAN</option>
                        <option value="MANAJEMEN LOGISTIK" @selected(request('konsentrasi')=='MANAJEMEN LOGISTIK')>MANAJEMEN LOGISTIK</option>
                        <option value="AKUNTANSI" @selected(request('konsentrasi')=='AKUNTANSI')>AKUNTANSI</option>
                        <option value="PERHOTELAN" @selected(request('konsentrasi')=='PERHOTELAN')>PERHOTELAN</option>
                        <option value="DESAIN KOMUNIKASI VISUAL" @selected(request('konsentrasi')=='DESAIN KOMUNIKASI VISUAL')>DESAIN KOMUNIKASI VISUAL</option>
                        <option value="PRODUKSI DAN SIARAN PROGRAM TELEVISI" @selected(request('konsentrasi')=='PRODUKSI DAN SIARAN PROGRAM TELEVISI')>PRODUKSI DAN SIARAN PROGRAM TELEVISI</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <button class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                        <a href="{{ route('absensi.export', request()->query()) }}" class="btn btn-success btn-sm" 
                           @if($data->total() > 500) 
                               title="Data akan dibatasi maksimal 1000 record untuk menghindari memory error"
                           @endif>
                            Export Excel
                            @if($data->total() > 500)
                                <small>(Max 1000)</small>
                            @endif
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <h6 class="mb-2">Total Presensi <small class="text-muted">({{ $scopeLabel ?? 'Semua' }})</small></h6>
                    <h3 class="mb-0 text-primary">{{ $totalAllRecords }}</h3>
                </div>
            </div>
            <div class="col-md-8">
                <div class="stats-card">
                    <h6 class="mb-3">Rekap per Sesi</h6>
                    @if($rekapPerSesi->count() > 0)
                        <div class="row">
                            @foreach($rekapPerSesi as $r)
                                @php $color = $sessionColors[$r['label']] ?? 'secondary'; @endphp
                                <div class="col-md-4 mb-3">
                                    <div class="small fw-semibold d-flex justify-content-between">
                                        <span>{{ $r['label'] }}</span>
                                        <span>{{ $r['total'] }} <span class="text-muted">({{ $r['percent'] }}%)</span></span>
                                    </div>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $r['percent'] }}%" aria-valuenow="{{ $r['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-end small text-muted">Total: {{ $totalAllRecords }} presensi ({{ $scopeLabel ?? 'Semua' }})</div>
                    @else
                        <p class="text-muted mb-0">Belum ada data presensi</p>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="stats-card h-100">
                    <h6 class="mb-3">Rekap per Kelas</h6>
                    @if(isset($rekapPerKelas) && $rekapPerKelas->count())
                        <div class="row">
                            @foreach($rekapPerKelas as $rk)
                                @php $percent = $totalAllRecords ? round($rk['total'] / $totalAllRecords * 100,1) : 0; @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="small d-flex justify-content-between">
                                        <span>{{ $rk['kelas'] }}</span>
                                        <span>{{ $rk['total'] }}</span>
                                    </div>
                                    <div class="progress" style="height:5px;">
                                        <div class="progress-bar bg-info" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada data</p>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="stats-card h-100">
                    <h6 class="mb-3">Rekap per Konsentrasi</h6>
                    @if(isset($rekapPerKonsentrasi) && $rekapPerKonsentrasi->count())
                        <div class="row">
                            @foreach($rekapPerKonsentrasi as $rk2)
                                @php $percent2 = $totalAllRecords ? round($rk2['total'] / $totalAllRecords * 100,1) : 0; @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="small d-flex justify-content-between">
                                        <span>{{ \Illuminate\Support\Str::limit($rk2['konsentrasi_keahlian'], 18) }}</span>
                                        <span>{{ $rk2['total'] }}</span>
                                    </div>
                                    <div class="progress" style="height:5px;">
                                        <div class="progress-bar bg-success" style="width: {{ $percent2 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada data</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="table-container">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Presensi PKL</h5>
                <small class="text-muted">Total: {{ $data->total() }} data</small>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th style="width: 100px;">Tanggal</th>
                            <th style="width: 80px;">Waktu</th>
                            <th style="width: 120px;">Sesi</th>
                            <th style="width: 150px;">Nama Siswa</th>
                            <th style="width: 80px;">Kelas</th>
                            <th style="width: 180px;">Konsentrasi</th>
                            <th style="width: 200px;">Perusahaan</th>
                            <th style="width: 120px;">Pemb. Sekolah</th>
                            <th style="width: 120px;">Pemb. DUDIKA</th>
                            <th style="width: 80px;">Foto</th>
                            <th style="width: 200px;">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td>{{ $loop->iteration + ($data->currentPage()-1)*$data->perPage() }}</td>
                                <td>
                                    @if($row->presensi_date instanceof \Carbon\Carbon)
                                        {{ $row->presensi_date->format('d/m/Y') }}
                                    @else
                                        {{ $row->presensi_date }}
                                    @endif
                                </td>
                                <td>
                                    @if($row->presensi_at instanceof \Carbon\Carbon)
                                        {{ $row->presensi_at->format('H:i:s') }}
                                    @elseif(is_string($row->presensi_at))
                                        {{ \Carbon\Carbon::parse($row->presensi_at)->format('H:i:s') }}
                                    @else
                                        {{ $row->presensi_at }}
                                    @endif
                                </td>
                                <td>
                                    {{ $row->sesi_normalized ?? $row->sesi_presensi }}
                                </td>
                                <td>{{ $row->nama_murid }}</td>
                                <td>
                                    <strong>{{ $row->kelas_normalized ?? $row->kelas }}</strong>
                                    @if($row->kelas_normalized !== $row->kelas)
                                        <small class="text-muted d-block">{{ $row->kelas }}</small>
                                    @endif
                                </td>
                                <td>{{ $row->konsentrasi_keahlian }}</td>
                                <td>{{ $row->nama_perusahaan }}</td>
                                <td>{{ $row->nama_pembimbing_sekolah }}</td>
                                <td>{{ $row->nama_pembimbing_dudika }}</td>
                                <td>
                                    @if($row->foto_path)
                                        <a href="{{ asset('storage/'.$row->foto_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $row->user_email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-4">
                                    <p class="text-muted mb-0">Tidak ada data presensi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($data->hasPages())
                <div class="p-3 border-top">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                        <small class="text-muted">
                            Menampilkan {{ $data->firstItem() }} sampai {{ $data->lastItem() }} dari {{ $data->total() }} data
                        </small>
                        <div class="d-flex justify-content-center">
                            {{ $data->appends(request()->query())->links('custom.pagination') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleMaintenance(action) {
            if (confirm(`Yakin ingin ${action === 'down' ? 'mengaktifkan' : 'menonaktifkan'} maintenance mode?`)) {
                // Show loading
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
                button.disabled = true;
                
                fetch(`/admin/maintenance/${action}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                       document.querySelector('input[name="_token"]')?.value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Gagal mengubah maintenance mode'));
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengubah maintenance mode');
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            }
        }
    </script>
</body>
</html>
