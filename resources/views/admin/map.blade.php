@extends('layouts.admin')

@section('content')
<div class="card shadow-sm" style="background-color: #ffffff; border: none;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #ffffff; border-bottom: none;">
        <a href="{{ route('admin.map') }}" class="text-decoration-none text-dark">
            <h5 class="card-title mb-0 fw-bold" style="font-size: 1.25rem;">Peta Lokasi WiFi</h5>
        </a>
        
        <a href="{{ route('admin.peta') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <!-- Search Bar -->
        <div class="input-group mb-3">
            <input type="text" id="searchWiFi" class="form-control" placeholder="Cari berdasarkan nama, lokasi, atau SSID...">
            <button id="searchButton" class="btn btn-primary">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
        
        <!-- Dropdown Filter -->
        <div class="mb-3">
            <label for="filterStatus" class="form-label">Filter Status WiFi:</label>
            <select id="filterStatus" class="form-select">
                <option value="all">Tampilkan Semua</option>
                <option value="online">Hanya WiFi Online</option>
                <option value="offline">Hanya WiFi Offline</option>
            </select>
        </div>

        <!-- Peta -->
        <div id="map" style="height: 600px; width: 100%;"></div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([-7.216241, 107.901479], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const greenIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    const wifiData = @json($wifi);
    const markers = [];

    wifiData.forEach(wifi => {
        const [latitude, longitude] = wifi.titik.split(',').map(coord => parseFloat(coord.trim()));
        const icon = wifi.status === 'Online' ? greenIcon : redIcon;
        const marker = L.marker([latitude, longitude], { icon }).addTo(map);
        
        marker.bindPopup(`
    <div style="
        font-family: Arial, sans-serif;
        font-size: 14px;
        line-height: 1.6;
        max-width: 260px;
        padding: 8px;
    ">
        <h6 style="margin-bottom: 5px; font-weight: bold; color: #333;">${wifi.nama}</h6>
        <p style="margin: 0;">
            <strong>Lihat Lokasi:</strong> 
            <a href="${wifi.lokasi}" target="_blank" style="color: #007bff; text-decoration: none;">
                <i class="fas fa-external-link-alt"></i> Lihat di Google Maps
            </a>
        </p>
        <p style="margin: 5px 0 0;">
            <strong>SSID:</strong> ${wifi.ssid}<br>
            <strong>Password:</strong> ${wifi.password}<br>
            <strong>Status:</strong> 
            <span style="
                font-weight: bold;
                color: ${wifi.status === 'Online' ? '#28a745' : '#dc3545'};
            ">
                ${wifi.status}
            </span>
        </p>
    </div>
`);

        
        markers.push({ marker, status: wifi.status, nama: wifi.nama.toLowerCase(), lokasi: wifi.lokasi.toLowerCase(), lat: latitude, lng: longitude });
    });

    function filterMarkers(status, searchText) {
        let foundMarker = null;
        markers.forEach(({ marker, status: markerStatus, nama, lokasi, lat, lng }) => {
            const matchesSearch = nama.includes(searchText) || lokasi.includes(searchText);
            if ((status === 'all' || markerStatus.toLowerCase() === status) && matchesSearch) {
                marker.addTo(map);
                if (!foundMarker) {
                    foundMarker = { lat, lng };
                }
            } else {
                marker.removeFrom(map);
            }
        });

        if (foundMarker) {
            map.setView([foundMarker.lat, foundMarker.lng], 15);
        }
    }

    document.getElementById('filterStatus').addEventListener('change', function() {
        const selectedStatus = this.value;
        const searchText = document.getElementById('searchWiFi').value.toLowerCase();
        filterMarkers(selectedStatus, searchText);
    });

    document.getElementById('searchButton').addEventListener('click', function() {
        const searchText = document.getElementById('searchWiFi').value.toLowerCase();
        const selectedStatus = document.getElementById('filterStatus').value;
        filterMarkers(selectedStatus, searchText);
    });

    document.getElementById('searchWiFi').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('searchButton').click();
        }
    });

    filterMarkers('all', '');
</script>
@endsection
