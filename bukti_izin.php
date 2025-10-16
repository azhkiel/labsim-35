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

// Jika tidak ada data
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
  <title>Bukti Cuti/Izin | Gruduk Cafe</title>
</head>

<body class="relative min-h-screen bg-cover bg-center" style="background-image:url('assets/Background 1.jpg');">

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-56 min-h-screen bg-gradient-to-b from-[#4e3b2e]/90 to-[#2f1e15]/90 
                 text-white flex flex-col justify-between p-6">
      <div class="space-y-4">
        <div class="flex items-center gap-2 font-semibold"><span class="text-white">Beranda</span></div>
        <a class="block w-full text-left bg-white text-yellow-900 py-2 px-3 rounded 
                  hover:bg-yellow-900 hover:text-white transition" href="riwayat kehadiran.php">
          Kehadiran
        </a>
        <a class="block w-full text-left bg-yellow-900 text-white py-2 px-3 rounded 
                  hover:bg-yellow-500 hover:text-yellow-900 transition" href="riwayat cuti izin.php">
          Cuti / Izin
        </a>
      </div>
      <a href="cuti izin karyawan.php" class="block text-center bg-white text-[#3b2f2a] font-semibold px-5 py-2 rounded-full 
                hover:bg-yellow-900 hover:text-white transition">
        Back
      </a>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 m-6 rounded-xl bg-white/95 shadow-[0_0_0_3px_#2b6cb0] relative overflow-hidden">
      <img src="assets/gruduk-watermark.png" alt="" aria-hidden="true"
        class="pointer-events-none select-none opacity-10 absolute inset-0 m-auto w-[820px] max-w-none">

      <div class="pt-8 text-center relative">
        <h1 class="text-[34px] leading-tight font-extrabold text-[#5a4336]">
          Bukti Cuti/Izin<br>
          <span class="tracking-wider"><?= htmlspecialchars($data['nama'] ?? '-') ?></span>
        </h1>
      </div>

      <div class="relative mx-auto mt-6 max-w-6xl grid grid-cols-12 gap-6 pb-12">
        <!-- Panel kiri -->
        <section class="col-span-3">
          <div class="h-full bg-[#e9e4df]/80 rounded-md flex items-center justify-center p-6">
            <p class="text-[#5b4a3f] font-extrabold text-xl leading-7 text-center">BUKTI CUTI/IZIN</p>
          </div>
        </section>

        <!-- Detail Bukti -->
        <section class="col-span-9">
          <div class="bg-gray-200 text-center text-[12px] tracking-wide text-gray-700 py-2 rounded">
            DETAIL BUKTI CUTI DAN IZIN
          </div>

          <div class="mt-3 rounded border border-gray-200 bg-white/70 overflow-x-auto p-6">
            <h2 class="text-xl font-semibold text-gray-800">Informasi Pengajuan Cuti/Izin</h2>

            <div class="grid grid-cols-3 gap-4 mt-4">
              <p class="text-gray-700 font-semibold">Nama</p>
              <p class="col-span-2 text-gray-700">: <?= htmlspecialchars($data['nama'] ?? '-') ?></p>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-2">
              <p class="text-gray-700 font-semibold">Jabatan</p>
              <p class="col-span-2 text-gray-700">: <?= htmlspecialchars($data['jabatan'] ?? '-') ?></p>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-2">
              <p class="text-gray-700 font-semibold">Departemen</p>
              <p class="col-span-2 text-gray-700">: <?= htmlspecialchars($data['departemen'] ?? '-') ?></p>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-2">
              <p class="text-gray-700 font-semibold">Jenis Cuti/Izin</p>
              <p class="col-span-2 text-gray-700">: <?= htmlspecialchars(ucfirst($data['tipe'] ?? '-')) ?></p>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-2">
              <p class="text-gray-700 font-semibold">Alasan</p>
              <p class="col-span-2 text-gray-700">: <?= htmlspecialchars($data['keterangan'] ?? '-') ?></p>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-2">
              <p class="text-gray-700 font-semibold">Tanggal Mulai</p>
              <p class="col-span-2 text-gray-700">: <?= date('d/m/Y', strtotime($data['tanggal_mulai'])) ?></p>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-2">
              <p class="text-gray-700 font-semibold">Tanggal Akhir</p>
              <p class="col-span-2 text-gray-700">: <?= date('d/m/Y', strtotime($data['tanggal_akhir'])) ?></p>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-2">
              <p class="text-gray-700 font-semibold">Status</p>
              <p class="col-span-2 text-gray-700">: 
                <?php 
                switch($data['action']) {
                  case 'diterima': echo 'Diterima'; break;
                  case 'ditolak': echo 'Ditolak'; break;
                  default: echo 'Pending'; break;
                }
                ?>
              </p>
            </div>

            <div class="mt-4 text-center">
              <a href="bukti_izin_print.php?id=<?= $data['id_cuti'] ?>"
                class="bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-700 transition">
                Download Bukti Cuti/Izin
              </a>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>
</body>
</html>