<?php
session_start();
include("config.php");

// Cek apakah user sudah login dan role-nya HRD
if (!isset($_SESSION['id_login']) || $_SESSION['role'] !== 'hrd') {
    header("Location: login.php");
    exit;
}

// Ambil data HRD yang sedang login
$id_login = $_SESSION['id_login'];
$query = "SELECT da.*, al.username 
          FROM detail_akun da 
          JOIN akun_login al ON da.id_login = al.id_login 
          WHERE da.id_login = '$id_login' AND al.role = 'hrd' 
          LIMIT 1";
$result = mysqli_query($conn, $query);
$hrd_data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard HRD | Gruduk Cafe</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    /* Sidebar bergambar gelap transparan */
    .side-photo {
      background-image: url('Assets/bg-sidebar.jpg');
      background-size: cover;
      background-position: center;
    }

    .side-overlay {
      backdrop-filter: blur(1px);
      background: rgba(59, 45, 34, .55);
    }

    /* Watermark logo besar di kanvas utama */
    .watermark {
      background-image: url('Assets/Logo Gruduk Cafe.png');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 65% auto;
      opacity: .18;
      pointer-events: none;
    }

    /* Tombol sidebar */
    .icon-pill {
      display: flex;
      align-items: center;
      gap: .75rem;
      width: 100%;
      border-radius: .6rem;
      background: rgba(255, 255, 255, .9);
      padding: .5rem .85rem;
      font-weight: 600;
      font-size: 13px;
      color: #374151;
      transition: background .2s ease, transform .1s ease;
      box-shadow: 0 1px 2px rgba(0, 0, 0, .06), 0 1px 3px rgba(0, 0, 0, .1);
    }

    .icon-pill:hover {
      background: #fff;
    }

    .icon-pill:active {
      transform: scale(.99);
    }
  </style>
</head>

<body class="min-h-screen bg-[#efe5cf]">

  <!-- LAYOUT: sidebar 360px + konten -->
  <div class="mx-auto max-w-[1500px] grid grid-cols-1 md:grid-cols-[360px,1fr] gap-6 px-3 md:px-8 py-6 items-start">

    <!-- SIDEBAR -->
    <aside class="">
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
          <a href="hrd_dashboard.php" class="icon-pill" style="background:#c8b699;">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <!-- home -->
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                fill="currentColor">
                <path d="M12 3l9 8-1.5 1.5L12 6l-7.5 6.5L3 11l9-8z" />
                <path d="M5 13h14v8H5z" />
              </svg>
            </span>
            <span class="label">Dashboard</span>
          </a>

          <a href="hrd_data_staff.php" class="icon-pill">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <!-- users -->
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                fill="currentColor">
                <path
                  d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3z" />
                <path
                  d="M8 13c-2.33 0-7 1.17-7 3.5V20h14v-3.5C15 14.17 10.33 13 8 13zM16 13c-.29 0-.62.02-.97.05 1.16.84 1.97 1.94 1.97 3.45V20h6v-3.5c0-2.33-4.67-3.5-7-3.5z" />
              </svg>
            </span>
            <span class="label">Data Karyawan</span>
          </a>

          <a href="hrd_cuti-izin.php" class="icon-pill">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <!-- mail -->
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                fill="currentColor">
                <path
                  d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 18V8.236l7.386 5.676a1 1 0 001.228 0L20 8.236V18H4z" />
              </svg>
            </span>
            <span class="label">Cuti / Izin</span>
          </a>

          <a href="hrd_absensi.php" class="icon-pill">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <!-- calendar -->
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .9-2 2v13a2 2 0 002 2h14a2 2 0 002-2V6c0-1.1-.89-2-2-2zm0 15H5V9h14v10z" />
              </svg>
            </span>
            <span class="label">Absensi</span>
          </a>

          <a href="hrd_biodata.php" class="icon-pill">
            <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
              <!-- single user -->
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

    <!-- KANVAS UTAMA -->
    <main>
      <div
        class="relative mx-auto w-full max-w-[1200px] rounded-2xl bg-white shadow-[0_20px_60px_-20px_rgba(0,0,0,.35)] min-h-[720px] overflow-hidden">
        <div class="absolute inset-0 watermark"></div>
        <div class="relative px-6 md:px-10 py-12 flex items-center justify-center text-center min-h-[720px]">
          <div class="max-w-2xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-extrabold tracking-wider text-[#4b3a2d] italic">WELCOME TO</h2>
            <h2 class="text-4xl md:text-5xl font-extrabold tracking-wider text-[#4b3a2d] mt-1 italic">GRUDUK TEAM!</h2>
            <p class="mt-6 text-2xl md:text-3xl font-semibold text-[#5b4a3c]">
              <?php echo htmlspecialchars($hrd_data['nama'] ?? 'Fahras Zeilicha'); ?>
              <?php echo !empty($hrd_data['jabatan']) ? ', ' . htmlspecialchars($hrd_data['jabatan']) : ', ST., MT'; ?>
            </p>
          </div>
        </div>
      </div>
    </main>

  </div>
</body>

</html>