<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Presensi PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h3 class="mb-3">Data Presensi PKL</h3>
    <div class="d-flex gap-2 mb-3">
        <a href="{{ route('absensi.create') }}" class="btn btn-sm btn-secondary">Kembali ke Form</a>
        <a href="{{ route('absensi.export') }}" class="btn btn-sm btn-success">Export Excel</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm align-middle">
            <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Waktu Presensi</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Konsentrasi</th>
                <th>Perusahaan</th>
                <th>Sesi</th>
                <th>Foto</th>
            </tr>
            </thead>
            <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $loop->iteration + ($data->currentPage()-1)*$data->perPage() }}</td>
                    <td>{{ $row->presensi_at }}</td>
                    <td>{{ $row->nama_murid }}</td>
                    <td>{{ $row->kelas }}</td>
                    <td>{{ $row->konsentrasi_keahlian }}</td>
                    <td>{{ $row->nama_perusahaan }}</td>
                    <td>{{ $row->sesi_presensi }}</td>
                    <td>
                        @if($row->foto_path)
                            <img src="{{ asset('storage/'.$row->foto_path) }}" alt="foto" style="width:60px;height:60px;object-fit:cover;">
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">Belum ada data</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $data->links() }}
</div>
</body>
</html>
