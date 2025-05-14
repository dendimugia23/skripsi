@extends('layouts.superadmin')

@section('content')
<div class="card shadow-sm border-0 bg-white">
    <!-- Header dan Tombol Export -->
    <div class="card-header bg-white border-0">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h5 class="fw-bold mb-2 mb-md-0" style="font-size: 1.25rem;">Rekapitulasi Pengaduan WiFi</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('superadmin.rekapitulasi.excel', request()->query()) }}" class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('superadmin.rekapitulasi.pdf', request()->query()) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Filter Bulan dan Tahun -->
        <form action="{{ route('superadmin.rekapitulasi') }}" method="GET" class="row g-2 mb-3">
            @php
                use Carbon\Carbon;

                $namaBulan = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];

                $selectedBulan = request('bulan');
                $selectedTahun = request('tahun', now()->year);

                $filterDate = null;
                if ($selectedBulan) {
                    $filterDate = Carbon::createFromDate($selectedTahun, $selectedBulan, 1)->endOfMonth();
                } else {
                    $filterDate = Carbon::createFromDate($selectedTahun, 12, 31); // akhir tahun
                }
            @endphp

            <div class="col-md-auto">
                <select name="bulan" class="form-select">
                    <option value="">-- Pilih Bulan --</option>
                    @foreach($namaBulan as $num => $name)
                        <option value="{{ $num }}" {{ $selectedBulan == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-auto">
                <select name="tahun" class="form-select">
                    <option value="">-- Pilih Tahun --</option>
                    @for($y = now()->year; $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $selectedTahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-auto">
                <a href="{{ route('superadmin.rekapitulasi') }}" class="btn btn-secondary">
                    <i class="fas fa-sync me-1"></i> Reset
                </a>
            </div>
        </form>

        <!-- Info Bulan -->
        <div class="mb-3">
            @if($selectedBulan)
                <strong>Menampilkan data bulan {{ $namaBulan[$selectedBulan] ?? '-' }} {{ $selectedTahun }}</strong>
            @else
                <strong>Menampilkan data tahun {{ $selectedTahun }}</strong>
            @endif
        </div>

        <!-- Tabel Rekapitulasi -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama / Lokasi WiFi</th>
                        <th>Status WiFi</th>
                        <th>Jumlah Pengaduan</th>
                        <th>Kategori Pengaduan</th>
                        <th>Total Pengguna</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $counter = 1; 
                        $totalWifiBaruBulanIni = 0;
                    @endphp

                    @forelse($rekap as $data)
                        @php
                            $wifiData = $wifiBaru->firstWhere('nama', $data['lokasi']);
                            $isBaru = false;
                            $createdAt = $wifiData ? Carbon::parse($wifiData->created_at) : null;

                            // Abaikan WiFi yang dibuat setelah tanggal filter
                            if ($createdAt && $createdAt->gt($filterDate)) {
                                continue;
                            }

                            if ($createdAt) {
                                if ($selectedBulan) {
                                    $isBaru = $createdAt->month == $selectedBulan && $createdAt->year == $selectedTahun;
                                } else {
                                    $isBaru = $createdAt->year == $selectedTahun;
                                }

                                if ($isBaru) {
                                    $totalWifiBaruBulanIni++;
                                }
                            }

                            $jumlahValid = isset($data['pengaduan']) 
                                ? collect($data['pengaduan'])->where('status_pengaduan', '!=', 'Ditolak')->count()
                                : ($data['jumlah_pengaduan'] ?? 0);

                            $kategoriValid = isset($data['pengaduan'])
                                ? collect($data['pengaduan'])->where('status_pengaduan', '!=', 'Ditolak')->pluck('kategori_pengaduan')->unique()->implode(', ')
                                : ($data['kategori_pengaduan'] ?? '-');
                        @endphp

                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td class="text-start">{{ $data['lokasi'] }}</td>
                            <td>
                                <span class="badge bg-{{ $data['status'] === 'Online' ? 'success' : 'danger' }}">
                                    {{ $data['status'] }}
                                </span>
                            </td>
                            <td>{{ $jumlahValid }}</td>
                            <td>{{ $kategoriValid ?: '-' }}</td>
                            <td>{{ $data['total_pengguna'] ?? 0 }}</td>
                            <td>
                                @if($isBaru)
                                    <span class="badge bg-info text-dark">WiFi Baru</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data WiFi atau pengaduan ditemukan.</td>
                        </tr>
                    @endforelse

                    <!-- Total WiFi Baru -->
                    <tr class="table-light fw-bold">
                        <td colspan="7" class="text-start">Total Titik WiFi Baru: {{ $totalWifiBaruBulanIni }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
