<?php
session_start();
include("config.php");

// Cek session
if (!isset($_SESSION['id_login']) || $_SESSION['role'] !== 'hrd') {
    header("Location: login.php");
    exit;
}

// Query untuk mengambil data absensi dari database
$query = "SELECT a.*, da.id_detail, da.nama, da.jabatan 
          FROM absen_karyawan a 
          JOIN detail_akun da ON a.id_detail = da.id_detail 
          ORDER BY a.tanggal_absen DESC";
$result = mysqli_query($conn, $query);

$absensi = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $absensi[] = $row;
    }
}

// Handle update action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_action'])) {
    $id_absen = mysqli_real_escape_string($conn, $_POST['id_absen']);
    $action = mysqli_real_escape_string($conn, $_POST['action']);
    
    $update_query = "UPDATE absen_karyawan SET action = '$action' WHERE id_absen = '$id_absen'";
    if (mysqli_query($conn, $update_query)) {
        $success_message = "Status absensi berhasil diupdate!";
        // Refresh page to show updated data
        echo "<script>window.location.href = 'hrd_absensi.php';</script>";
        exit;
    } else {
        $error_message = "Gagal mengupdate status absensi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Absensi HRD | Gruduk Cafe</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* ====== Gambar latar sidebar (kiri) ====== */
    .side-photo {
      background-image: url('Assets/bg-sidebar.jpg');
      background-size: cover;
      background-position: center;
    }

    .side-overlay {
      background: rgba(59, 45, 34, .55);
      backdrop-filter: blur(1px);
    }

    /* ====== Watermark besar di kanvas utama ====== */
    .watermark {
      background-image: url('Assets/Logo Gruduk Cafe.png');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 62% auto;
      opacity: .10;
      pointer-events: none;
    }

    /* ====== Tombol sidebar:*/
    .icon-pill {
      display: inline-flex;
      align-items: center;
      gap: .75rem;
      width: 100%;
      padding: .5rem .75rem;
      height: 2.75rem;
      border-radius: .5rem;
      font-weight: 600;
      font-size: 13px;
      color: #1f2937;
      background: rgba(255, 255, 255, .92);
      transition: .15s;
      box-shadow: 0 1px 3px rgba(0, 0, 0, .12);
    }

    .icon-pill:hover {
      background: #fff;
    }

    .icon-pill svg {
      display: block;
    }

    .icon-pill .label {
      line-height: 1;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.8);
    }

    .modal-content {
      margin: 5% auto;
      display: block;
      max-width: 90%;
      max-height: 90%;
      border-radius: 8px;
    }

    .close {
      position: absolute;
      top: 15px;
      right: 35px;
      color: #fff;
      font-size: 40px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: #ccc;
    }

    /* Status badge styles */
    .status-badge {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }
    
    .status-pending {
      background-color: #fef3c7;
      color: #92400e;
    }
    
    .status-diterima {
      background-color: #d1fae5;
      color: #065f46;
    }
    
    .status-ditolak {
      background-color: #fee2e2;
      color: #991b1b;
    }
  </style>
</head>

<body class="min-h-screen bg-[#efe5cf]">

  <!-- ===== LAYOUT ===== -->
  <div class="max-w-[120rem] mx-auto grid grid-cols-12 gap-4 md:gap-6 px-3 md:px-8 py-4">

    <!-- ===== SIDEBAR ===== -->
    <aside class="col-span-12 md:col-span-4 lg:col-span-3">
      <div class="side-photo rounded-xl overflow-hidden">
        <div class="side-overlay p-4 md:p-6 min-h-screen flex flex-col gap-3 text-white">

          <!-- Brand di atas sidebar -->
          <div class="flex items-center gap-3 mb-5">
            <img src="Assets/logo gruduk new.png" alt="Gruduk Cafe"
              class="w-10 h-10 md:w-12 md:h-12 object-contain drop-shadow">
            <div class="text-2xl font-extrabold tracking-wide text-white leading-tight">
              Gruduk <span class="text-[#efe5cf]">Cafe</span>
            </div>
          </div>

          <!-- Dashboard -->
          <a href="hrd_dashboard.php" class="icon-pill">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 3l9 8-1.5 1.5L12 6l-7.5 6.5L3 11l9-8z" />
                <path d="M5 13h14v8H5z" />
              </svg>
            </span>
            <span class="label">Dashboard</span>
          </a>

          <!-- Data Karyawan -->
          <a href="hrd_data_staff.php" class="icon-pill">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3z" />
                <path
                  d="M8 13c-2.33 0-7 1.17-7 3.5V20h14v-3.5C15 14.17 10.33 13 8 13zM16 13c-.29 0-.62.02-.97.05 1.16.84 1.97 1.94 1.97 3.45V20h6v-3.5c0-2.33-4.67-3.5-7-3.5z" />
              </svg>
            </span>
            <span class="label">Data Karyawan</span>
          </a>

          <!-- Cuti / Izin -->
          <a href="hrd_cuti-izin.php" class="icon-pill">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 18V8.236l7.386 5.676a1 1 0 001.228 0L20 8.236V18H4z" />
              </svg>
            </span>
            <span class="label">Cuti / Izin</span>
          </a>

          <!-- Absensi (aktif / ditandai warna) -->
          <a href="hrd_absensi.php" class="icon-pill" style="background:#c8b699;">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .9-2 2v13a2 2 0 002 2h14a2 2 0 002-2V6c0-1.1-.89-2-2-2zm0 15H5V9h14v10z" />
              </svg>
            </span>
            <span class="label">Absensi</span>
          </a>

          <!-- Biodata -->
          <a href="hrd_biodata.php" class="icon-pill">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
              </svg>
            </span>
            <span class="label text-gray-800">Biodata</span>
          </a>

          <!-- Log out -->
          <div class="mt-auto">
            <a href="logout.php"
              class="w-full rounded-full bg-[#6b4b3e] hover:bg-[#b38963] text-white font-semibold text-sm py-2 shadow block text-center">
              Log out
            </a>
          </div>

        </div>
      </div>
    </aside>

    <!-- ===== KONTEN ABSENSI ===== -->
    <main class="col-span-12 md:col-span-8 lg:col-span-9">
      <div class="relative rounded-xl bg-white min-h-screen overflow-hidden flex flex-col">
        <!-- watermark -->
        <div class="absolute inset-0 watermark"></div>

        <div class="relative p-6 md:p-8">
          <!-- Judul -->
          <div class="mx-auto max-w-7xl w-full">
            <div class="rounded-md bg-[#b6a187] text-center">
              <h1 class="py-3 md:py-4 text-3xl md:text-4xl font-extrabold italic tracking-wide text-[#4b3a2d]">
                ABSENSI
              </h1>
            </div>
          </div>

          <!-- Tabel -->
          <div class="mt-6 mx-auto max-w-7xl w-full">
            <!-- Header -->
            <div class="grid grid-cols-12 gap-4 items-center">
              <div class="col-span-2 text-center bg-gray-100 py-2 rounded-md text-sm font-semibold text-gray-700">ID</div>
              <div class="col-span-3 text-center bg-gray-100 py-2 rounded-md text-sm font-semibold text-gray-700">NAMA</div>
              <div class="col-span-2 text-center bg-gray-100 py-2 rounded-md text-sm font-semibold text-gray-700">JABATAN</div>
              <div class="col-span-2 text-center bg-gray-100 py-2 rounded-md text-sm font-semibold text-gray-700">FOTO ABSEN</div>
              <div class="col-span-3 text-center bg-gray-100 py-2 rounded-md text-sm font-semibold text-gray-700">AKSI</div>
            </div>

            <?php if (!empty($absensi)): ?>
              <?php foreach ($absensi as $absen): ?>
                <div class="mt-4 grid grid-cols-12 gap-4 items-center">
                  <div class="col-span-2 text-center bg-gray-100 py-2 rounded-md text-sm">
                    <?php echo htmlspecialchars($absen['id_absen']); ?>
                  </div>
                  <div class="col-span-3 text-center">
                    <div class="bg-white/80 border border-gray-200 rounded-md px-3 py-2 text-sm">
                      <?php echo htmlspecialchars($absen['nama']); ?>
                    </div>
                  </div>
                  <div class="col-span-2 text-center bg-white/80 border border-gray-200 rounded-md px-3 py-2 text-sm">
                    <?php echo htmlspecialchars($absen['jabatan']); ?>
                  </div>
                  <div class="col-span-2 flex items-center justify-center">
                    <?php if (!empty($absen['foto_absen'])): ?>
                      <button onclick="openModal('<?php echo htmlspecialchars($absen['foto_absen']); ?>')"
                        class="w-9 h-9 rounded-md border border-gray-300 bg-white shadow-sm inline-flex items-center justify-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]/30 transition"
                        aria-label="Lihat foto absen <?php echo htmlspecialchars($absen['nama']); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                          stroke="currentColor" stroke-width="1.75">
                          <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                      </button>
                    <?php else: ?>
                      <span class="text-gray-400 text-sm">Tidak ada foto</span>
                    <?php endif; ?>
                  </div>
                  <div class="col-span-3 flex items-center justify-center gap-2">
                    <!-- Status Badge -->
                    <span class="status-badge 
                      <?php
                      switch ($absen['action']) {
                          case 'diterima': echo 'status-diterima'; break;
                          case 'ditolak': echo 'status-ditolak'; break;
                          case 'pending':
                          default: echo 'status-pending'; break;
                      }
                      ?>">
                      <?php
                      switch ($absen['action']) {
                          case 'diterima': echo 'Diterima'; break;
                          case 'ditolak': echo 'Ditolak'; break;
                          case 'pending':
                          default: echo 'Pending'; break;
                      }
                      ?>
                    </span>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-1">
                      <form method="POST" action="" class="inline">
                        <input type="hidden" name="id_absen" value="<?php echo htmlspecialchars($absen['id_absen']); ?>">
                        <input type="hidden" name="action" value="diterima">
                        <input type="hidden" name="update_action" value="1">
                        <button type="submit" 
                                class="w-8 h-8 bg-green-500 text-white rounded-md flex items-center justify-center hover:bg-green-600 transition"
                                title="Setujui">
                          ✓
                        </button>
                      </form>

                      <form method="POST" action="" class="inline">
                        <input type="hidden" name="id_absen" value="<?php echo htmlspecialchars($absen['id_absen']); ?>">
                        <input type="hidden" name="action" value="ditolak">
                        <input type="hidden" name="update_action" value="1">
                        <button type="submit" 
                                class="w-8 h-8 bg-red-500 text-white rounded-md flex items-center justify-center hover:bg-red-600 transition"
                                title="Tolak">
                          ✕
                        </button>
                      </form>

                      <form method="POST" action="" class="inline">
                        <input type="hidden" name="id_absen" value="<?php echo htmlspecialchars($absen['id_absen']); ?>">
                        <input type="hidden" name="action" value="pending">
                        <input type="hidden" name="update_action" value="1">
                        <button type="submit" 
                                class="w-8 h-8 bg-yellow-500 text-white rounded-md flex items-center justify-center hover:bg-yellow-600 transition"
                                title="Pending">
                          ↻
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="mt-4 text-center py-8 text-gray-500">
                Tidak ada data absensi.
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal untuk menampilkan foto -->
  <div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
  </div>

  <script>
    // Fungsi untuk membuka modal
    function openModal(imageSrc) {
      const modal = document.getElementById('imageModal');
      const modalImg = document.getElementById('modalImage');
      modal.style.display = 'block';
      modalImg.src = 'uploads/absensi/' + imageSrc;
    }

    // Fungsi untuk menutup modal
    function closeModal() {
      document.getElementById('imageModal').style.display = 'none';
    }

    // Tutup modal ketika klik di luar gambar
    window.onclick = function(event) {
      const modal = document.getElementById('imageModal');
      if (event.target == modal) {
        closeModal();
      }
    }

    // Tutup modal dengan tombol ESC
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeModal();
      }
    });
  </script>
</body>
</html>