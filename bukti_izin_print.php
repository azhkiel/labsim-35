<?php
include("config.php");
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['id_login'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID cuti dari URL
$id = $_GET['id'] ?? 0;

// Ambil data dari database berdasarkan ID
$query = mysqli_query($conn, "SELECT c.*, da.nama, da.jabatan, da.departemen 
                              FROM cuti_izin_karyawan c 
                              JOIN detail_akun da ON c.id_detail = da.id_detail 
                              WHERE c.id_cuti = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$data) {
  die("Data tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>Bukti Izin / Leave Slip | Gruduk Cafe</title>
  <style>
    /* Reset untuk print */
    @media print {
      @page {
        margin: 0.5in;
        size: A4 portrait;
      }
      
      body {
        margin: 0;
        padding: 0;
        background: white !important;
        color: black !important;
        font-family: "Times New Roman", Times, serif;
        visibility: visible !important;
      }
      
      .no-print {
        display: none !important;
      }
      
      .print-section {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        width: 100% !important;
        height: auto !important;
        background: white !important;
        color: black !important;
        box-shadow: none !important;
        border: none !important;
      }
      
      .action-buttons,
      .print-button {
        display: none !important;
      }
      
      /* Pastikan semua elemen dalam print-section visible */
      .print-section * {
        visibility: visible !important;
        color: black !important;
        background: transparent !important;
      }
      
      /* Hilangkan background images */
      body::before,
      body::after {
        display: none !important;
      }
      
      /* Style khusus untuk print */
      .print-section img:not([src*="logo"]) {
        display: none;
      }
      
      .watermark {
        opacity: 0.1 !important;
      }
    }

    /* Style untuk screen */
    .print-section {
      background: white;
      color: black;
    }
  </style>
</head>

<body class="relative min-h-screen bg-cover bg-center no-print" style="background-image:url('assets/Background 1.jpg');">

  <!-- Action Buttons -->
  <div class="action-buttons no-print p-4 bg-white/90 shadow-sm">
    <div class="max-w-4xl mx-auto flex justify-between items-center">
      <a href="riwayat cuti izin.php" 
         class="flex items-center gap-2 bg-[#6b4b3e] text-white px-4 py-2 rounded-lg hover:bg-[#5a3f32] transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Riwayat
      </a>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <div class="flex justify-center items-start p-4 md:p-8 no-print">
    <main class="w-full max-w-4xl rounded-xl bg-white/95 shadow-2xl relative overflow-hidden print-section">
      <img src="assets/gruduk-watermark.png" alt="" aria-hidden="true"
        class="pointer-events-none select-none opacity-10 absolute inset-0 m-auto w-[820px] max-w-none watermark">

      <!-- Section yang akan dicetak -->
      <div class="p-8 relative z-10">
        <!-- HEADER -->
        <div class="flex justify-between items-start mb-8 border-b-2 border-gray-300 pb-6">
          <div>
            <img src="Assets/logo gruduk new.png" alt="Gruduk Cafe" class="w-16 h-16 mb-2">
            <h1 class="text-2xl font-bold text-[#5a4336]">Gruduk Cafe</h1>
            <p class="text-sm text-gray-600">Jl. Contoh Raya No. 123, Jakarta</p>
          </div>
          <div class="text-right">
            <h2 class="text-[28px] font-extrabold text-[#5a4336]">Bukti Izin / Leave Slip</h2>
            <p class="text-sm text-gray-600 mt-2">No. <?= htmlspecialchars($data['id_cuti'] ?? '') ?></p>
          </div>
        </div>

        <!-- INFORMASI UTAMA -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <div class="space-y-4">
            <div>
              <label class="block font-semibold text-gray-700 mb-1">Nama Karyawan</label>
              <p class="text-gray-800"><?= htmlspecialchars($data['nama'] ?? '-') ?></p>
            </div>
            <div>
              <label class="block font-semibold text-gray-700 mb-1">ID Karyawan</label>
              <p class="text-gray-800"><?= htmlspecialchars($data['id_detail'] ?? '-') ?></p>
            </div>
            <div>
              <label class="block font-semibold text-gray-700 mb-1">Departemen</label>
              <p class="text-gray-800"><?= htmlspecialchars($data['departemen'] ?? '-') ?></p>
            </div>
            <div>
              <label class="block font-semibold text-gray-700 mb-1">Jabatan</label>
              <p class="text-gray-800"><?= htmlspecialchars($data['jabatan'] ?? '-') ?></p>
            </div>
          </div>
          
          <div class="space-y-4">
            <div>
              <label class="block font-semibold text-gray-700 mb-1">Jenis Izin</label>
              <p class="text-gray-800"><?= htmlspecialchars(ucfirst($data['tipe'] ?? '-')) ?></p>
            </div>
            <div>
              <label class="block font-semibold text-gray-700 mb-1">Status</label>
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold
                <?= $data['action'] == 'diterima' ? 'bg-green-100 text-green-800' : 
                   ($data['action'] == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                <span class="w-2 h-2 rounded-full 
                  <?= $data['action'] == 'diterima' ? 'bg-green-500' : 
                     ($data['action'] == 'ditolak' ? 'bg-red-500' : 'bg-yellow-500') ?> 
                  inline-block"></span>
                <?php 
                switch($data['action']) {
                  case 'diterima': echo 'Diterima'; break;
                  case 'ditolak': echo 'Ditolak'; break;
                  default: echo 'Pending'; break;
                }
                ?>
              </span>
            </div>
            <div>
              <label class="block font-semibold text-gray-700 mb-1">Tanggal Mulai</label>
              <p class="text-gray-800"><?= date('d/m/Y', strtotime($data['tanggal_mulai'])) ?></p>
            </div>
            <div>
              <label class="block font-semibold text-gray-700 mb-1">Tanggal Akhir</label>
              <p class="text-gray-800"><?= date('d/m/Y', strtotime($data['tanggal_akhir'])) ?></p>
            </div>
          </div>
        </div>

        <!-- TANGGAL PENGAJUAN -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
          <label class="block font-semibold text-gray-700 mb-1">Tanggal Pengajuan</label>
          <p class="text-gray-800"><?= date('d/m/Y H:i', strtotime($data['tanggal_pengajuan'])) ?></p>
        </div>

        <!-- ALASAN -->
        <div class="mb-6">
          <label class="block font-semibold text-gray-700 mb-2">Alasan Izin</label>
          <div class="p-4 bg-gray-50 rounded-lg min-h-[80px]">
            <p class="text-gray-800"><?= htmlspecialchars($data['keterangan'] ?? '-') ?></p>
          </div>
        </div>

        <!-- LAMPIRAN -->
        <div class="mb-8">
          <label class="block font-semibold text-gray-700 mb-2">Lampiran</label>
          <div class="p-4 bg-gray-50 rounded-lg">
            <?php if (!empty($data['foto_cuti_izin'])): ?>
              <p class="text-gray-800">Surat keterangan — tersimpan di sistem</p>
            <?php else: ?>
              <p class="text-gray-600 italic">Tidak ada lampiran</p>
            <?php endif; ?>
          </div>
        </div>

        <!-- TANDA TANGAN -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8 text-center text-gray-700 border-t-2 border-gray-300 pt-8">
          <div>
            <p class="font-semibold mb-8">Diajukan oleh,</p>
            <div class="mt-16 font-bold text-lg border-t border-gray-400 pt-4"><?= htmlspecialchars($data['nama'] ?? '-') ?></div>
            <p class="text-sm text-gray-600 mt-2">Karyawan</p>
          </div>
          <div>
            <p class="font-semibold mb-8">Mengetahui,</p>
            <div class="mt-16 font-bold text-lg border-t border-gray-400 pt-4">Fahras Zeilicha</div>
            <p class="text-sm text-gray-600 mt-2">HRD</p>
          </div>
          <div>
            <p class="font-semibold mb-8">Disetujui oleh,</p>
            <div class="mt-16 font-bold text-lg border-t border-gray-400 pt-4">Firmansyah</div>
            <p class="text-sm text-gray-600 mt-2">Atasan Langsung</p>
          </div>
        </div>

        <!-- FOOTER -->
        <div class="mt-12 text-center text-xs text-gray-500 border-t border-gray-200 pt-4">
          <p>Dokumen ini dicetak secara elektronik dan tidak memerlukan tanda tangan basah</p>
          <p class="mt-1">Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
        </div>
      </div>
    </main>
  </div>

  <script>
    function printDocument() {
      // Tambahkan class khusus sebelum print
      document.body.classList.add('printing');
      
      // Tunggu sebentar untuk memastikan class diterapkan
      setTimeout(() => {
        window.print();
        
        // Hapus class setelah print selesai
        setTimeout(() => {
          document.body.classList.remove('printing');
        }, 500);
      }, 100);
    }

    // Event listener untuk after print
    window.addEventListener('afterprint', function() {
      document.body.classList.remove('printing');
    });
  </script>

</body>
</html>