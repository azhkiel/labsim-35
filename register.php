<?php
require('config.php');

$error_message = '';
$success_message = '';

if (isset($_POST['form1'])) {
    $valid = 1;

    // Ambil data dari form dan hindari SQL Injection
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $agama = mysqli_real_escape_string($conn, $_POST['agama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $departemen = mysqli_real_escape_string($conn, $_POST['departemen']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    
    // Set default values
    $role = 'karyawan';
    $status = 'Aktif';

    // Validasi kosong
    if (empty($nama) || empty($username) || empty($password) || empty($email)) {
        $valid = 0;
        $error_message = "Nama, Username, Password, dan Email wajib diisi.<br>";
    }
    
    if ($password !== $confirm_password) {
        $valid = 0;
        $error_message .= "Konfirmasi password tidak cocok.<br>";
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $valid = 0;
        $error_message .= "Format email tidak valid.<br>";
    }

    // Cek username duplikat di akun_login
    $check_username = mysqli_query($conn, "SELECT * FROM akun_login WHERE username = '$username'");
    if (mysqli_num_rows($check_username) > 0) {
        $valid = 0;
        $error_message .= "Username sudah digunakan.<br>";
    }

    // Cek email duplikat di detail_akun
    $check_email = mysqli_query($conn, "SELECT * FROM detail_akun WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $valid = 0;
        $error_message .= "Email sudah digunakan.<br>";
    }

    // Jika valid, masukkan ke database
    if ($valid == 1) {
        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // 1. Insert ke tabel akun_login
            $query1 = "INSERT INTO akun_login (username, password, role) VALUES (?, ?, ?)";
            $stmt1 = mysqli_prepare($conn, $query1);
            mysqli_stmt_bind_param($stmt1, "sss", $username, $password_hash, $role);
            
            if (!mysqli_stmt_execute($stmt1)) {
                throw new Exception("Gagal menyimpan data login: " . mysqli_error($conn));
            }
            
            $id_login = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt1);

            // 2. Insert ke tabel detail_akun
            $query2 = "INSERT INTO detail_akun (
                id_login, nama, alamat, tempat_lahir, tanggal_lahir, 
                jenis_kelamin, agama, email, departemen, jabatan, status, role
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt2 = mysqli_prepare($conn, $query2);
            mysqli_stmt_bind_param(
                $stmt2, 
                "isssssssssss", 
                $id_login, $nama, $alamat, $tempat_lahir, $tanggal_lahir,
                $jenis_kelamin, $agama, $email, $departemen, $jabatan, $status, $role
            );
            
            if (!mysqli_stmt_execute($stmt2)) {
                throw new Exception("Gagal menyimpan detail akun: " . mysqli_error($conn));
            }
            
            mysqli_stmt_close($stmt2);

            // Commit transaction
            mysqli_commit($conn);
            
            $success_message = "Registrasi berhasil! Silakan login.";
            
            // Redirect ke login page setelah 2 detik
            header("refresh:2;url=login.php");
            
        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            $error_message = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar | Kafe Gruduk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-cover bg-center min-h-screen flex flex-col"
  style="background-image: url('assets/Background 1.jpg');">

  <!-- ===== NAVBAR ===== -->
  <header class="w-full bg-white/90 backdrop-blur shadow-md fixed top-0 z-10">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3">
      <a href="#" class="flex items-center gap-2">
        <img src="assets/logo gruduk new.png" class="w-10 h-10 object-contain" alt="Gruduk Cafe">
        <h1 class="text-2xl font-extrabold text-[#3b2f2a]">Kafe <span class="text-[#0c3e73]">Gruduk</span></h1>
      </a>
      <nav class="hidden md:flex gap-6 text-sm font-semibold text-gray-700">
        <a href="index.php#home" class="hover:text-[#6b4b3e]">Rumah</a>
        <a href="index.php#about" class="hover:text-[#6b4b3e]">Tentang Kami</a>
        <a href="index.php#team" class="hover:text-[#6b4b3e]">Tim Kami</a>
        <a href="index.php#contact" class="hover:text-[#6b4b3e]">Kontak</a>
        <a href="index.php#location" class="hover:text-[#6b4b3e]">Lokasi</a>
      </nav>
    </div>
  </header>

  <!-- ===== REGISTER SECTION ===== -->
  <main class="flex flex-col items-center justify-center flex-grow pt-24 px-4">
    <div class="flex flex-col md:flex-row bg-white/95 rounded-2xl shadow-2xl overflow-hidden w-full max-w-4xl transition-all">

      <!-- Gambar Kiri -->
      <div class="md:w-2/5 w-full h-[250px] md:h-auto">
        <img src="assets/register picture.jpg" alt="Cafe" class="object-cover w-full h-full">
      </div>

      <!-- Form Kanan -->
      <div class="md:w-3/5 w-full p-6 flex flex-col justify-center">
        <h1 class="text-2xl font-bold text-center mb-3 text-gray-800">Daftar Karyawan Baru</h1>

        <?php if ($error_message != ''): ?>
          <div class="p-3 mb-3 bg-red-100 text-red-700 rounded-lg text-sm border border-red-300">
            <?php echo $error_message; ?>
          </div>
        <?php endif; ?>

        <?php if ($success_message != ''): ?>
          <div class="p-3 mb-3 bg-green-100 text-green-700 rounded-lg text-sm border border-green-300">
            <?php echo $success_message; ?>
          </div>
        <?php endif; ?>

        <form method="post" name="form1" class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <!-- Data Login -->
          <div class="md:col-span-2">
            <h3 class="font-semibold text-gray-700 mb-2 text-sm border-b pb-1">Data Login</h3>
          </div>
          
          <div class="md:col-span-2">
            <input type="text" name="nama" placeholder="Nama Lengkap *" required
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
          </div>

          <div>
            <input type="text" name="username" placeholder="Username *" required
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
          </div>

          <div>
            <input type="password" name="password" placeholder="Password *" required
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
          </div>
          <div>
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password *" required
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
          </div>
          <!-- Data Pribadi -->
          <div class="md:col-span-2 mt-2">
            <h3 class="font-semibold text-gray-700 mb-2 text-sm border-b pb-1">Data Pribadi</h3>
          </div>

          <div class="md:col-span-2">
            <input type="email" name="email" placeholder="Email *" required
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
          </div>

          <div>
            <input type="text" name="tempat_lahir" placeholder="Tempat Lahir"
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
          </div>

          <div>
            <input type="date" name="tanggal_lahir"
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
          </div>

          <div>
            <select name="jenis_kelamin"
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
              <option value="">Jenis Kelamin</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>

          <div>
            <select name="agama"
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
              <option value="">Agama</option>
              <option value="Islam">Islam</option>
              <option value="Kristen">Kristen</option>
              <option value="Katolik">Katolik</option>
              <option value="Hindu">Hindu</option>
              <option value="Buddha">Buddha</option>
              <option value="Konghucu">Konghucu</option>
            </select>
          </div>

          <!-- Data Pekerjaan -->
          <div class="md:col-span-2 mt-2">
            <h3 class="font-semibold text-gray-700 mb-2 text-sm border-b pb-1">Data Pekerjaan</h3>
          </div>

          <div>
            <select name="departemen"
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
              <option value="">Pilih Departemen</option>
              <option value="Dapur">Dapur</option>
              <option value="Pelayanan">Pelayanan</option>
              <option value="Kasir">Kasir</option>
              <option value="Manajemen">Manajemen</option>
              <option value="Bar">Bar</option>
            </select>
          </div>

          <div>
            <select name="jabatan"
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
              <option value="">Pilih Jabatan</option>
              <option value="Staff">Staff</option>
              <option value="Waitress">Waitress</option>
              <option value="Barista">Barista</option>
              <option value="Kasir">Kasir</option>
              <option value="Chef">Chef</option>
              <option value="Manager">Manager</option>
            </select>
          </div>

          <div class="md:col-span-2">
            <textarea name="alamat" placeholder="Alamat"
              class="w-full px-3 py-2 bg-gray-100 rounded-md border focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent h-16"
            ></textarea>
          </div>

          <div class="md:col-span-2">
            <button type="submit" name="form1"
              class="w-full bg-[#6b4b3e] text-white font-semibold py-2.5 rounded-md mt-2 hover:bg-[#5a3f32] transition duration-300">
              Daftar Sekarang
            </button>
          </div>
        </form>

        <p class="text-center text-sm mt-3 text-gray-600">
          Sudah punya akun? <a href="login.php" class="text-[#6b4b3e] hover:underline font-semibold">Masuk di sini</a>
        </p>
      </div>
    </div>
  </main>
</body>
</html>