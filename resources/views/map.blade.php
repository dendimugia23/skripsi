<!-- Lokasi Section -->
<section id="map" class="map section">
  <div class="container-fluid">
    <!-- Search Bar dan Filter -->
    <div class="row mb-3 justify-content-between">
      <div class="col-md-6">
        <div class="input-group">
          <input type="text" class="form-control" id="searchWiFi" placeholder="Cari WiFi...">
          <button class="btn btn-primary" id="searchButton">Cari</button>
        </div>
      </div>
      <div class="col-md-3">
        <select class="form-select" id="filterStatus">
          <option value="all">Semua Status</option>
          <option value="online">Online</option>
          <option value="offline">Offline</option>
        </select>
      </div>
    </div>
    <div class="row gy-4">
      <div class="col-12" style="height: 600px; width: 100%;" id="map-container"></div>
    </div>
  </div>
</section>

<!-- Script untuk LeafletJS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const map = L.map('map-container').setView([-7.216241, 107.901479], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const greenIcon = L.icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34]
    });

    const redIcon = L.icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34]
    });

    const wifiData = @json($wifi);
    const markers = [];

    wifiData.forEach(wifi => {
      const [latitude, longitude] = wifi.titik.split(',').map(coord => parseFloat(coord.trim()));
      const icon = wifi.status.toLowerCase() === 'online' ? greenIcon : redIcon;
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
      markers.push({ marker, status: wifi.status.toLowerCase(), nama: wifi.nama.toLowerCase(), lokasi: wifi.lokasi.toLowerCase(), lat: latitude, lng: longitude });
    });

    function filterMarkers(status, searchText) {
      let foundMarker = null;
      markers.forEach(({ marker, status: markerStatus, nama, lokasi, lat, lng }) => {
        const matchesSearch = nama.includes(searchText) || lokasi.includes(searchText);
        if ((status === 'all' || markerStatus === status) && matchesSearch) {
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
      filterMarkers(this.value, document.getElementById('searchWiFi').value.toLowerCase());
    });

    document.getElementById('searchButton').addEventListener('click', function() {
      filterMarkers(document.getElementById('filterStatus').value, document.getElementById('searchWiFi').value.toLowerCase());
    });

    document.getElementById('searchWiFi').addEventListener('keypress', function(event) {
      if (event.key === 'Enter') {
        event.preventDefault();
        document.getElementById('searchButton').click();
      }
    });

    filterMarkers('all', '');
  });
</script>


