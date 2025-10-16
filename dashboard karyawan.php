<?php
session_start();
include("config.php");

// Cek apakah user sudah login
if (!isset($_SESSION['id_login'])) {
    header("Location: login.php");
    exit;
}

// Ambil data karyawan yang sedang login
$id_login = $_SESSION['id_login'];
$query = "SELECT da.*, al.username 
          FROM detail_akun da 
          JOIN akun_login al ON da.id_login = al.id_login 
          WHERE da.id_login = '$id_login' AND al.role = 'karyawan' 
          LIMIT 1";
$result = mysqli_query($conn, $query);
$karyawan = mysqli_fetch_assoc($result);

// Proses absensi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['foto_absen'])) {
    $id_detail = $karyawan['id_detail'];
    
    // Handle file upload
    $target_dir = "uploads/absensi/";
    
    // Buat folder jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = pathinfo($_FILES["foto_absen"]["name"], PATHINFO_EXTENSION);
    $filename = "absen_" . $karyawan['id_detail'] . "_" . date("Y-m-d_H-i-s") . "." . $file_extension;
    $target_file = $target_dir . $filename;
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["foto_absen"]["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($_FILES["foto_absen"]["tmp_name"], $target_file)) {
            // Insert data absensi ke database
            $insert_query = "INSERT INTO absen_karyawan (id_detail, foto_absen, action) 
                            VALUES ('$id_detail', '$filename', 'pending')";
            if (mysqli_query($conn, $insert_query)) {
                $success_message = "Absensi berhasil dikirim! Menunggu verifikasi HRD.";
            } else {
                $error_message = "Gagal menyimpan data absensi.";
            }
        } else {
            $error_message = "Maaf, terjadi error saat upload file.";
        }
    } else {
        $error_message = "File bukan gambar.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>Dashboard Karyawan | Gruduk Cafe</title>
</head>

<body class="relative min-h-screen backdrop-blur-sm bg-cover bg-center"
  style="background-image: url('assets/Background\ 1.jpg');">

  <div class="flex min-h-[calc(100vh-64px)]">
    <!-- ===== SIDEBAR ===== -->
    <aside class="w-64 bg-white/80 flex flex-col p-6 shadow-lg min-h-screen">

      <!-- Dashboard -->
      <a href="dashboard karyawan.php"
        class="flex items-center w-full bg-yellow-900 text-white border border-yellow-900 px-3 py-2 gap-3 font-bold hover:bg-yellow-500 hover:text-white transition">
        <span><!-- icon -->&#9776;</span>
        Dashboard
      </a>

      <!-- Cuti / Izin -->
      <a href="cuti izin karyawan.php"
        class="flex items-center w-full bg-white text-yellow-900 border border-yellow-900 rounded px-3 py-2 my-3 gap-3 font-semibold hover:bg-yellow-900 hover:text-white transition">
        Cuti / Izin
      </a>

      <!-- ===== RIWAYAT (button dropdown) ===== -->
      <button id="btnRiwayat" aria-expanded="false"
        class="flex items-center justify-between w-full bg-white text-yellow-900 border border-yellow-900 rounded px-3 py-2 mb-2 font-semibold hover:bg-yellow-900 hover:text-white transition">
        <span class="flex items-center gap-3">
          <!-- contoh icon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 24 24" fill="none"
            stroke="currentColor">
            <circle cx="12" cy="12" r="10" />
            <path d="M14 10l-4 4m0-4l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Riwayat
        </span>
        <!-- chevron -->
        <svg id="chevRiwayat" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-300"
          viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd"
            d="M5.23 7.21a.75.75 0 011.06.02L10 11.06l3.71-3.83a.75.75 0 111.08 1.04l-4.24 4.38a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
            clip-rule="evenodd" />
        </svg>
      </button>

      <!-- Submenu dengan animasi max-height -->
      <div id="submenuRiwayat" class="overflow-hidden transition-[max-height] duration-300 max-h-0">
        <div class="ml-4 mt-2 flex flex-col gap-2 pb-2">
          <a href="riwayat kehadiran.php"
            class="px-3 py-2 rounded border border-yellow-900 text-yellow-900 bg-white hover:bg-yellow-900 hover:text-white transition">
            Riwayat Kehadiran
          </a>
          <a href="riwayat cuti izin.php"
            class="px-3 py-2 rounded border border-yellow-900 text-yellow-900 bg-white hover:bg-yellow-900 hover:text-white transition">
            Riwayat Cuti / Izin
          </a>
        </div>
      </div>

      <!-- Biodata -->
      <a href="biodata karyawan.php"
        class="flex items-center w-full bg-white text-yellow-900 border-yellow-900 rounded px-3 py-2 gap-3 font-semibold hover:bg-yellow-900 hover:text-white transition">
        Biodata
      </a>

      <!-- Logout -->
      <a href="logout.php"
        class="mt-auto w-full text-yellow-900 border border-yellow-900 rounded px-3 py-2 font-bold bg-white hover:bg-yellow-900 hover:text-white transition">
        Log out
      </a>
    </aside>

    <!-- ===== SCRIPT DROPDOWN ===== -->
    <script>
      const btn = document.getElementById('btnRiwayat');
      const submenu = document.getElementById('submenuRiwayat');
      const chev = document.getElementById('chevRiwayat');

      function expand() {
        submenu.style.maxHeight = submenu.scrollHeight + 'px';
        btn.setAttribute('aria-expanded', 'true');
        chev.classList.add('rotate-180');
      }
      function collapse() {
        submenu.style.maxHeight = '0px';
        btn.setAttribute('aria-expanded', 'false');
        chev.classList.remove('rotate-180');
      }

      // Toggle saat klik
      btn.addEventListener('click', () => {
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        expanded ? collapse() : expand();
      });

      // Buka otomatis jika URL mengarah ke halaman riwayat*
      const shouldOpen =
        location.hash === '#riwayat' ||
        /riwayat-(kehadiran|cuti)/i.test(location.pathname);
      if (shouldOpen) expand();
    </script>

    <!-- ===== KONTEN (center vertikal & horizontal) ===== -->
     <main class="flex-1 flex items-center justify-center">
        <div class="bg-white bg-opacity-80 rounded-lg shadow-lg py-12 px-8 w-full max-w-4xl text-center relative flex flex-col items-center">
          
          <!-- Watermark logo tengah -->
          <img src="assets/Logo Gruduk Cafe.png" alt="logo"
            class="pointer-events-none select-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[480px] opacity-10">

          <h1 class="text-5xl md:text-6xl font-bold text-yellow-900 mb-4 italic"
            style="font-family:Arial, Helvetica, sans-serif; letter-spacing:1px;">
            WELCOME TO<br>GRUDUK TEAM!
          </h1>

          <div class="text-2xl font-semibold text-gray-700 mb-4">
            <?php echo htmlspecialchars($karyawan['nama'] ?? 'Karyawan'); ?>
            <?php echo !empty($karyawan['jabatan']) ? ', ' . htmlspecialchars($karyawan['jabatan']) : ''; ?>
          </div>

          <!-- Foto profil di tengah -->
          <div class="rounded-xl overflow-hidden shadow-lg border border-gray-300 w-[175px] h-[200px] bg-gray-100 flex items-center justify-center relative"> <!-- Foto Profil yang dapat dipilih --> 
            <img id="profileImage" src="<?php if (!empty($karyawan['foto_profile'])) 
              { echo 'uploads/fotoProfile/' . htmlspecialchars($karyawan['foto_profile']); 
              } else { echo 'assets/pp.png'; } ?>" class="w-full h-full object-cover" alt="Foto Karyawan"> </div>

          <form action="" method="POST" enctype="multipart/form-data" class="space-y-4 w-full max-w-sm">
            <label class="block text-left">
              <span class="text-gray-700 font-semibold">Upload Foto Absen</span>
              <input type="file" name="foto_absen" accept="image/*" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </label>
            <button type="submit" class="bg-yellow-900 text-white px-10 py-2 rounded-full font-bold shadow-md hover:bg-yellow-800 transition">
              ABSEN
            </button>
          </form>

        </div>
      </main>

  </div>
</body>
</html>