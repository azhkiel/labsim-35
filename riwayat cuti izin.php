<?php
include("config.php");
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['id_login'])) {
    header("Location: login.php");
    exit;
}

// Ambil data karyawan yang sedang login
$id_login = $_SESSION['id_login'];
$query_karyawan = "SELECT da.* FROM detail_akun da WHERE da.id_login = '$id_login' LIMIT 1";
$result_karyawan = mysqli_query($conn, $query_karyawan);
$karyawan = mysqli_fetch_assoc($result_karyawan);

// Ambil riwayat cuti/izin dari database
$id_detail = $karyawan['id_detail'];
$result = mysqli_query($conn, "SELECT * FROM cuti_izin_karyawan WHERE id_detail = '$id_detail' ORDER BY tanggal_pengajuan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>Riwayat Cuti/Izin | Gruduk Cafe</title>
</head>

<body class="relative min-h-screen bg-cover bg-center" style="background-image:url('assets/Background 1.jpg');">

  <div class="flex min-h-screen">
    <aside class="w-56 min-h-screen bg-gradient-to-b from-[#4e3b2e]/90 to-[#2f1e15]/90 text-white flex flex-col justify-between p-6">
      <div class="space-y-4">
        <div class="flex items-center gap-2 font-semibold"><span class="text-white">Beranda</span></div>
        <a class="block w-full text-left bg-white text-yellow-900 py-2 px-3 rounded hover:bg-yellow-900 hover:text-white transition"
          href="riwayat kehadiran.php">Kehadiran</a>
        <a class="block w-full text-left bg-yellow-900 text-white py-2 px-3 rounded hover:bg-yellow-500 hover:text-yellow-900 transition"
          href="riwayat cuti izin.php">Cuti / Izin</a>
      </div>
      <a href="cuti izin karyawan.php"
        class="block text-center bg-white text-[#3b2f2a] font-semibold px-5 py-2 rounded-full hover:bg-yellow-900 hover:text-white transition">Back</a>
    </aside>


    <main class="flex-1 m-6 rounded-xl bg-white/95 shadow-[0_0_0_3px_#2b6cb0] relative overflow-hidden">
      <!-- Watermark -->
      <img src="assets/gruduk-watermark.png" alt="" aria-hidden="true"
        class="pointer-events-none select-none opacity-10 absolute inset-0 m-auto w-[820px] max-w-none">

      <!-- Header -->
      <div class="pt-8 text-center relative">
        <h1 class="text-[34px] leading-tight font-extrabold text-[#5a4336]">
          <?= htmlspecialchars($karyawan['nama'] ?? 'Karyawan') ?>'s
          <br><span class="tracking-wider"><?= htmlspecialchars($karyawan['id_detail'] ?? 'ID') ?></span>
        </h1>
      </div>

      <!-- Konten -->
      <div class="relative mx-auto mt-6 max-w-6xl grid grid-cols-12 gap-6 pb-12">

        <!-- Panel kiri -->
        <section class="col-span-3">
          <div class="h-full bg-[#e9e4df]/80 rounded-md flex items-center justify-center p-6">
            <p class="text-[#5b4a3f] font-extrabold text-xl leading-7 text-center">
              RIWAYAT<br />CUTI/IZIN
            </p>
          </div>
        </section>

        <!-- Panel kanan -->
        <section class="col-span-9">
          <div class="bg-gray-200 text-center text-[12px] tracking-wide text-gray-700 py-2 rounded">
            REKAP CUTI DAN IZIN STAFF
          </div>

          <div class="mt-3 rounded border border-gray-200 overflow-x-auto bg-white/70">
            <table class="table-fixed w-full text-sm">
              <thead class="bg-gray-100 text-gray-700">
                <tr>
                  <th class="py-2 px-4 text-left min-w-[160px]">TANGGAL</th>
                  <th class="py-2 px-4 text-center min-w-[160px]">STATUS</th>
                  <th class="py-2 px-4 text-center min-w-[200px]">KETERANGAN</th>
                  <th class="py-2 px-4 text-center min-w-[200px]">STATUS</th>
                  <th class="py-2 px-4 text-center min-w-[200px]">BUKTI</th> 
                </tr>
              </thead>
              <tbody class="text-gray-800">
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                  <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr class="border-t border-gray-200 hover:bg-yellow-50 transition">
                      <td class="py-2 px-4 text-center"><?= htmlspecialchars($row['id_cuti'] ?? '-') ?></td>
                      <td class="py-2 px-4 text-center"><?= date('d/m/Y H:i', strtotime($row['tanggal_pengajuan'])) ?></td>
                      <td class="py-2 px-4 text-center"><?= htmlspecialchars(ucfirst($row['tipe'] ?? '-')) ?></td>
                      <td class="py-2 px-4 text-center">
                        <?php if (($row['action'] ?? '') === 'diterima') : ?>
                          <span class="inline-block px-4 py-1 rounded-full bg-green-200 text-green-900 font-semibold">Diterima</span>
                        <?php elseif (($row['action'] ?? '') === 'ditolak') : ?>
                          <span class="inline-block px-4 py-1 rounded-full bg-red-200 text-red-900 font-semibold">Ditolak</span>
                        <?php else : ?>
                          <span class="inline-block px-6 py-1 rounded-full bg-yellow-200 text-yellow-900 font-semibold">Pending</span>
                        <?php endif; ?>
                      </td>
                      <td class="py-2 px-4 text-center">
                        <?php if (($row['action'] ?? '') === 'diterima') : ?>
                          <!-- Tombol aktif jika status diterima -->
                          <a href="bukti_izin.php?id=<?= $row['id_cuti'] ?>"
                            class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-900 transition cursor-pointer">
                            Print
                          </a>
                        <?php else : ?>
                          <!-- Tombol non-aktif jika status bukan diterima -->
                          <span class="bg-gray-400 text-gray-200 px-3 py-1 rounded cursor-not-allowed">
                            Print
                          </span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500 italic">
                      Belum ada data cuti/izin.
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </main>

</body>
</html>