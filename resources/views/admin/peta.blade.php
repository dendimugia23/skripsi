@extends('layouts.admin')

@section('content')
<div class="card shadow-sm" style="background-color: #ffffff; border: none;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #ffffff; border-bottom: none;">
        <a href="{{ route('admin.peta') }}" class="text-decoration-none text-dark">
            <h5 class="card-title mb-0 fw-bold" style="font-size: 1.25rem;">Daftar WiFi</h5>
        </a>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah
            </a>
            <a href="{{ route('admin.map') }}" class="btn btn-success btn-sm">
                <i class="fas fa-map-marker-alt"></i> Peta
            </a>
            <a href="{{ route('admin.wifi.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Search Bar -->
        <div class="mb-3">
            <form action="{{ route('admin.peta') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama, lokasi, atau SSID..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        @if($wifi->isEmpty())
            <div class="alert alert-info">Tidak ada data WiFi yang tersedia.</div>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Lokasi WiFi</th>
                        <th>Lihat Lokasi</th>
                        <th>Titik</th>
                        <th>SSID</th>
                        <th>Password</th>
                        <th>Status Validasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wifi as $index => $data)
                    <tr>
                        <td>{{ ($wifi->currentPage() - 1) * $wifi->perPage() + $index + 1 }}</td>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data->lokasi }}</td>
                        <td>{{ $data->titik }}</td>
                        <td>{{ $data->ssid }}</td>
                        <td>{{ $data->password }}</td>
                        <td>
                            <span class="badge bg-{{ $data->status_validasi == 'Pending' ? 'warning' : ($data->status_validasi == 'Ditolak' ? 'danger' : 'success') }}">
                                {{ $data->status_validasi }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $data->status == 'Online' ? 'success' : 'danger' }}">
                                {{ $data->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.edit', $data->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.destroy', $data->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus WiFi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $wifi->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
