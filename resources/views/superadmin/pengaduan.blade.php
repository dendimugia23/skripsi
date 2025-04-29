@extends('layouts.superadmin')

@section('content')
<div class="card shadow-sm" style="background-color: #ffffff; border: none;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #ffffff; border-bottom: none;">
        <h5 class="card-title mb-0 fw-bold" style="font-size: 1.25rem;">Daftar Pengaduan</h5>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <form action="{{ route('superadmin.pengaduan') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Nomor Tiket, Nama Wifi, atau Kategori..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Tiket</th>
                    <th>Nama WiFi</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>KTP</th>
                    <th>Status</th>
                    <th>Validasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengaduan as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->ticket_number }}</td>
                    <td>{{ $data->nama_wifi }}</td>
                    <td>{{ $data->kategori_pengaduan }}</td>
                    <td>{{ $data->deskripsi_pengaduan }}</td>
                    <td>
                        @if($data->image_pengaduan)
                            <a href="{{ asset('storage/' . $data->image_pengaduan) }}" target="_blank">
                                <img src="{{ asset('storage/' . $data->image_pengaduan) }}" alt="Gambar Pengaduan" class="img-thumbnail" style="max-width: 100px;">
                            </a>
                        @else
                            <span class="text-muted">Tidak ada gambar</span>
                        @endif
                    </td>
                    <td>
                        @if($data->image_ktp)
                            <a href="{{ asset('storage/' . $data->image_ktp) }}" target="_blank">
                                <img src="{{ asset('storage/' . $data->image_ktp) }}" alt="Foto KTP" class="img-thumbnail" style="max-width: 100px;">
                            </a>
                        @else
                            <span class="text-muted">Tidak ada KTP</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ 
                            $data->status_pengaduan == 'Selesai' || $data->status_pengaduan == 'Tervalidasi' ? 'success' :
                            ($data->status_pengaduan == 'Ditolak' ? 'danger' : 'warning') 
                        }}">
                            {{ $data->status_pengaduan }}
                        </span>
                    </td>
                    <td>
                        @if($data->status_pengaduan === 'Proses')
                            <span class="badge bg-secondary">Belum Diverifikasi</span>
                        @elseif($data->status_pengaduan === 'Tervalidasi')
                            <span class="badge bg-success">Sudah Diverifikasi</span>
                        @elseif($data->status_pengaduan === 'Ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">Status Tidak Dikenal</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">Data pengaduan tidak ada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $pengaduan->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
