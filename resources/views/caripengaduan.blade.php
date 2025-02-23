<section id="search" class="search section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Pencarian Pengaduan</h2>
    <p>Cari status pengaduan Anda berdasarkan nomor tiket</p>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row gy-4">
      <div class="col-lg-12">
        <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
          <i class="bi bi-ticket-detailed"></i>
          <h3>Nomor Tiket</h3>
          <p>Masukkan nomor tiket yang diberikan saat pengaduan</p>
        </div>
      </div><!-- End Info Item -->
    </div>

    <div class="row gy-4 mt-1">
      <div class="col-lg-6 mx-auto">
        <form id="searchForm" class="php-search-form" data-aos="fade-up" data-aos-delay="400">
          @csrf <!-- Tambahkan CSRF Token untuk keamanan -->
          <div class="row gy-4">
            <div class="col-md-12">
              <input type="text" id="ticket_number" name="ticket_number" class="form-control" placeholder="Nomor Tiket" required>
            </div>

            <div class="col-md-12 text-center">
             
              <div class="error-message" style="display: none; color: red;"></div>
              <div class="sent-message" style="display: none;">Pencarian berhasil, silakan cek status pengaduan Anda!</div>

              <button type="submit">Cari Pengaduan</button>
            </div>
          </div>
        </form>
      </div><!-- End Search Form -->
    </div>

    <!-- Hasil Pencarian -->
    <div class="row gy-4 mt-4">
      <div class="col-lg-8 mx-auto">
        <div id="searchResult" class="alert alert-info" style="display: none;">
          <h4>Hasil Pencarian</h4>
          <p><strong>Nomor Tiket:</strong> <span id="resultTicket"></span></p>
          <p><strong>Status:</strong> <span id="resultStatus"></span></p>
          
        </div>
      </div>
    </div>

  </div>
</section>

<!-- jQuery dan AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $("#searchForm").submit(function(e) {
      e.preventDefault(); // Mencegah reload halaman

      let ticketNumber = $("#ticket_number").val(); // Ambil inputan nomor tiket

     
      $(".error-message").hide();
      $(".sent-message").hide();
      $("#searchResult").hide();

      $.ajax({
        url: "{{ route('search.pengaduan') }}", // Menggunakan route Laravel
        type: "POST",
        data: { 
          ticket_number: ticketNumber,
          _token: "{{ csrf_token() }}" // Sertakan CSRF Token
        },
        success: function(response) {
          $(".loading").hide();

          if (response.success) {
            $("#resultTicket").text(response.ticket_number);
            $("#resultStatus").text(response.status_pengaduan);
            $("#resultDescription").text(response.description_pengaduan);
            $("#searchResult").show();
            $(".sent-message").show();
          } else {
            $(".error-message").text(response.message).show();
          }
        },
        error: function() {
          $(".loading").hide();
          $(".error-message").text("Nomor Tiket Salah / Tidak ada").show();
        }
      });
    });
  });
</script>