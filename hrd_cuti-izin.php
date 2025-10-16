<?php
session_start();
include("config.php");

// Cek session
if (!isset($_SESSION['id_login']) || $_SESSION['role'] !== 'hrd') {
    header("Location: login.php");
    exit;
}

// Query untuk mengambil data cuti/izin dari database
$query = "SELECT c.*, da.nama, da.jabatan 
          FROM cuti_izin_karyawan c 
          JOIN detail_akun da ON c.id_detail = da.id_detail 
          ORDER BY c.tanggal_pengajuan DESC";
$result = mysqli_query($conn, $query);

$cuti_izin = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cuti_izin[] = $row;
    }
}

// Handle update action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_action'])) {
    $id_cuti = mysqli_real_escape_string($conn, $_POST['id_cuti']);
    $action = mysqli_real_escape_string($conn, $_POST['action']);
    
    $update_query = "UPDATE cuti_izin_karyawan SET action = '$action' WHERE id_cuti = '$id_cuti'";
    if (mysqli_query($conn, $update_query)) {
        $success_message = "Status cuti/izin berhasil diupdate!";
        // Refresh page to show updated data
        echo "<script>window.location.href = 'hrd_cuti-izin.php';</script>";
        exit;
    } else {
        $error_message = "Gagal mengupdate status cuti/izin.";
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cuti / Izin | Gruduk Cafe</title>
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

    /* ====== Watermark di kanvas utama ====== */
    .watermark {
      background-image: url('Assets/Logo Gruduk Cafe.png');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 58% auto;
      opacity: .18;
      pointer-events: none;
    }

    /* ====== Tombol sidebar ====== */
    .icon-pill {
      display: inline-flex;
      align-items: center;
      gap: .75rem;
      width: 100%;
      padding: .5rem .9rem;
      height: 3rem;
      border-radius: .6rem;
      font-weight: 600;
      font-size: 14px;
      color: #1f2937;
      background: #ffffff;
      transition: .15s;
      box-shadow: 0 1px 3px rgba(0, 0, 0, .12);
    }

    .icon-pill:hover {
      background: #fff;
      box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
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
      background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border-radius: 8px;
      width: 90%;
      max-width: 400px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: #000;
    }
  </style>
</head>

<body class="min-h-screen bg-[#efe5cf]">

  <!-- ===== LAYOUT (tanpa navbar) ===== -->
  <div class="max-w-[120rem] mx-auto grid grid-cols-12 gap-4 md:gap-6 px-3 md:px-6 py-4 items-start">

    <!-- ===== SIDEBAR ===== -->
    <aside class="col-span-12 md:col-span-4 lg:col-span-3">
      <div class="side-photo rounded-xl overflow-hidden shadow-lg">
        <div class="side-overlay p-5 min-h-screen flex flex-col gap-3">

          <!-- Brand di atas sidebar -->
          <div class="flex items-center gap-3 mb-5">
            <img src="Assets/logo gruduk new.png" alt="Gruduk Cafe"
              class="w-10 h-10 md:w-12 md:h-12 object-contain drop-shadow">
            <div class="text-2xl font-extrabold tracking-wide text-white leading-tight">
              Gruduk <span class="text-[#efe5cf]">Cafe</span>
            </div>
          </div>

          <!-- Menu -->
          <a href="hrd_dashboard.php" class="icon-pill">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 3l9 8-1.5 1.5L12 6l-7.5 6.5L3 11l9-8z" />
                <path d="M5 13h14v8H5z" />
              </svg>
            </span>
            <span class="label">Dashboard</span>
          </a>

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

          <a href="hrd_cuti-izin.php" class="icon-pill" style="background:#c8b699;">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 18V8.236l7.386 5.676a1 1 0 001.228 0L20 8.236V18H4z" />
              </svg>
            </span>
            <span class="label">Cuti / Izin</span>
          </a>

          <a href="hrd_absensi.php" class="icon-pill">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .9-2 2v13a2 2 0 002 2h14a2 2 0 002-2V6c0-1.1-.89-2-2-2zm0 15H5V9h14v10z" />
              </svg>
            </span>
            <span class="label">Absensi</span>
          </a>

          <a href="hrd_biodata.php" class="icon-pill">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
              </svg>
            </span>
            <span class="label">Biodata</span>
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

    <!-- ===== KANVAS UTAMA (kartu putih besar) ===== -->
    <main class="col-span-12 md:col-span-8 lg:col-span-9">
      <div
        class="relative rounded-xl bg-white shadow-[0_20px_60px_-20px_rgba(0,0,0,.35)] min-h-screen overflow-hidden flex flex-col">
        <!-- watermark -->
        <div class="absolute inset-0 watermark"></div>

        <div class="relative p-6 md:p-8 flex-1">
          <!-- Judul lebar tan -->
          <div class="mx-auto max-w-5xl">
            <div class="rounded-lg bg-[#b6a187] text-center">
              <h1 class="py-4 text-4xl md:text-5xl font-extrabold italic tracking-wide text-[#4b3a2d]">
                CUTI / IZIN KARYAWAN
              </h1>
            </div>
          </div>

          <!-- Tabel -->
          <div class="mt-6 mx-auto max-w-5xl">
            <div class="overflow-hidden rounded-lg ring-1 ring-black/5 bg-white/90">
              <table class="min-w-full table-fixed">
                <thead class="bg-gray-200">
                  <tr>
                    <th class="w-[14%] px-4 py-3 text-center text-sm md:text-base font-semibold text-gray-700">Tanggal</th>
                    <th class="w-[36%] px-4 py-3 text-center text-sm md:text-base font-semibold text-gray-700">Nama Lengkap</th>
                    <th class="w-[25%] px-4 py-3 text-center text-sm md:text-base font-semibold text-gray-700">Jabatan</th>
                    <th class="w-[17%] px-4 py-3 text-center text-sm md:text-base font-semibold text-gray-700">Action</th>
                    <th class="w-[8%]  px-4 py-3 text-center text-sm md:text-base font-semibold text-gray-700">Detail</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <?php if (!empty($cuti_izin)): ?>
                    <?php foreach ($cuti_izin as $cuti): ?>
                      <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-center text-gray-800 font-medium">
                          <?php echo date('d/m/Y', strtotime($cuti['tanggal_pengajuan'])); ?>
                        </td>
                        <td class="px-4 py-2">
                          <div class="mx-auto max-w-sm text-center bg-white border border-gray-200 rounded-md px-3 py-2 text-gray-900">
                            <?php echo htmlspecialchars($cuti['nama']); ?>
                          </div>
                        </td>
                        <td class="px-4 py-2 text-center text-gray-800">
                          <?php echo htmlspecialchars($cuti['jabatan']); ?>
                        </td>
                        <td class="px-4 py-2">
                          <div class="flex justify-center items-center gap-2">
                            <?php
                            $status_class = '';
                            $status_text = '';
                            
                            switch ($cuti['action']) {
                                case 'diterima':
                                    $status_class = 'bg-green-200 text-green-900';
                                    $status_text = 'Diterima';
                                    break;
                                case 'ditolak':
                                    $status_class = 'bg-rose-200 text-rose-900';
                                    $status_text = 'Ditolak';
                                    break;
                                case 'pending':
                                default:
                                    $status_class = 'bg-yellow-200 text-yellow-900';
                                    $status_text = 'Pending';
                                    break;
                            }
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full <?php echo $status_class; ?> text-sm font-semibold">
                              <?php echo htmlspecialchars($status_text); ?>
                            </span>
                            <button onclick="openModal(<?php echo $cuti['id_cuti']; ?>, '<?php echo $cuti['nama']; ?>', '<?php echo $cuti['action']; ?>')"
                              class="inline-flex items-center justify-center p-1 rounded-md bg-gray-100 border border-gray-300 hover:bg-gray-200 transition"
                              title="Ubah Status">
                              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                              </svg>
                            </button>
                          </div>
                        </td>
                        <td class="px-4 py-2">
                          <div class="flex justify-center">
                            <?php if (!empty($cuti['foto_cuti_izin'])): ?>
                              <a href="uploads/cuti_izin/<?php echo htmlspecialchars($cuti['foto_cuti_izin']); ?>" download
                                class="inline-flex items-center justify-center p-2 rounded-md bg-white border border-gray-200 hover:bg-gray-100 hover:shadow focus:outline-none focus:ring-2 focus:ring-[#4b3a2d]/30 transition"
                                aria-label="Unduh surat <?php echo htmlspecialchars($cuti['nama']); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#4b3a2d]" viewBox="0 0 24 24" fill="currentColor">
                                  <path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.103.897 2 2 2h12a2 2 0 0 0 2-2V8l-6-6zM13 9V3.5L18.5 9H13z" />
                                  <path d="M12 12v4.586l1.293-1.293 1.414 1.414L12 20.414l-2.707-2.707 1.414-1.414L11 16.586V12h2z" />
                                </svg>
                              </a>
                            <?php else: ?>
                              <span class="inline-flex items-center justify-center p-2 rounded-md bg-gray-100 border border-gray-200 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                  <path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.103.897 2 2 2h12a2 2 0 0 0 2-2V8l-6-6zM13 9V3.5L18.5 9H13z" />
                                  <path d="M12 12v4.586l1.293-1.293 1.414 1.414L12 20.414l-2.707-2.707 1.414-1.414L11 16.586V12h2z" />
                                </svg>
                              </span>
                            <?php endif; ?>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        Tidak ada data cuti/izin.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div> <!-- /ring -->
          </div> <!-- /max-w-5xl -->
        </div> <!-- /content padding -->
      </div> <!-- /card -->
    </main>
  </div><!-- /grid -->

  <!-- Modal untuk mengubah status -->
  <div id="statusModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 class="text-xl font-bold text-gray-800 mb-4">Ubah Status Cuti/Izin</h2>
      <p class="text-gray-600 mb-4">Karyawan: <span id="modalNama" class="font-semibold"></span></p>
      
      <form method="POST" action="" id="statusForm">
        <input type="hidden" name="id_cuti" id="modalIdCuti">
        <input type="hidden" name="update_action" value="1">
        
        <div class="space-y-3">
          <div class="flex items-center">
            <input type="radio" id="statusDiterima" name="action" value="diterima" class="mr-2">
            <label for="statusDiterima" class="flex items-center">
              <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
              Diterima
            </label>
          </div>
          
          <div class="flex items-center">
            <input type="radio" id="statusPending" name="action" value="pending" class="mr-2">
            <label for="statusPending" class="flex items-center">
              <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
              Pending
            </label>
          </div>
          
          <div class="flex items-center">
            <input type="radio" id="statusDitolak" name="action" value="ditolak" class="mr-2">
            <label for="statusDitolak" class="flex items-center">
              <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
              Ditolak
            </label>
          </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition">
            Batal
          </button>
          <button type="submit" class="px-4 py-2 bg-[#6b4b3e] text-white rounded-md hover:bg-[#b38963] transition">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Fungsi untuk membuka modal
    function openModal(idCuti, nama, currentStatus) {
      const modal = document.getElementById('statusModal');
      document.getElementById('modalIdCuti').value = idCuti;
      document.getElementById('modalNama').textContent = nama;
      
      // Set radio button sesuai status saat ini
      document.getElementById('statusDiterima').checked = currentStatus === 'diterima';
      document.getElementById('statusPending').checked = currentStatus === 'pending';
      document.getElementById('statusDitolak').checked = currentStatus === 'ditolak';
      
      modal.style.display = 'block';
    }

    // Fungsi untuk menutup modal
    function closeModal() {
      document.getElementById('statusModal').style.display = 'none';
    }

    // Event listener untuk tombol close
    document.querySelector('.close').addEventListener('click', closeModal);

    // Tutup modal ketika klik di luar modal
    window.onclick = function(event) {
      const modal = document.getElementById('statusModal');
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