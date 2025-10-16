<?php
session_start();
include("config.php");

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error_message = "Kolom username dan password harus diisi.";
    } else {
        // Ambil user dari database gruduk_cafe
        $query = "SELECT al.*, da.nama, da.role as user_role 
                  FROM akun_login al 
                  LEFT JOIN detail_akun da ON al.id_login = da.id_login 
                  WHERE al.username = '$username' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Verifikasi password yang di-hash
            if ($password === $row['password']) {
                // Set session data
                $_SESSION['id_login'] = $row['id_login'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['user_role'] = $row['user_role'];

                // Redirect berdasarkan role
                switch ($row['role']) {
                    case 'admin':
                        header("Location: admin_dashboard.php");
                        break;
                    case 'hrd':
                        header("Location: hrd_dashboard.php");
                        break;
                    case 'karyawan':
                        header("Location: karyawan_dashboard.php");
                        break;
                    default:
                        header("Location: dashboard.php");
                        break;
                }
                exit;
            } else {
                $error_message = "Username atau password salah.";
            }
        } else {
            $error_message = "Username tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login HRD | Kafe Gruduk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-fixed bg-center bg-cover" style="background-image:url('Assets/bg coffe dan logo.png');">

  <!-- NAVBAR -->
  <header class="w-full bg-[#d4c29a] shadow">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3">
      <a href="index.php" class="flex items-center gap-3">
        <img src="Assets/logo gruduk new.png" class="w-10 h-10 object-contain">
        <span class="text-2xl font-extrabold text-[#3b2f2a]">Kafe <span class="text-[#0c3e73]">Gruduk</span></span>
      </a>
      <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-gray-700">
        <a href="index.php#home" class="hover:text-[#6b4b3e]">Home</a>
        <a href="index.php#about" class="hover:text-[#6b4b3e]">Tentang Kami</a>
        <a href="index.php#team" class="hover:text-[#6b4b3e]">Tim Kami</a>
        <a href="index.php#contact" class="hover:text-[#6b4b3e]">Kontak</a>
        <a href="index.php#location" class="hover:text-[#6b4b3e]">Lokasi</a>
      </nav>
    </div>
  </header>

  <!-- LOGIN CARD -->
  <main class="flex items-center justify-center min-h-[calc(100vh-80px)] p-6">
    <section class="w-full max-w-md rounded-3xl bg-[#e6dcc4]/90 backdrop-blur-sm shadow-[0_20px_60px_-10px_rgba(0,0,0,0.45)] px-8 py-10">
      <h1 class="text-3xl font-bold text-center text-[#3b2f2a] mb-6">Login HRD</h1>

      <?php if (!empty($error_message)): ?>
        <div class="bg-red-200 text-red-800 text-sm p-2 rounded mb-4 text-center">
          <?= htmlspecialchars($error_message) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="">
        <label class="block text-sm font-semibold text-[#3b2f2a] mb-1">Username</label>
        <input type="text" name="username" class="w-full mb-4 rounded-md border border-[#cfc9b5] px-3 py-2 text-sm focus:ring-2 focus:ring-[#6b4b3e] outline-none">

        <label class="block text-sm font-semibold text-[#3b2f2a] mb-1">Password</label>
        <input type="password" name="password" id="password" class="w-full mb-4 rounded-md border border-[#cfc9b5] px-3 py-2 text-sm focus:ring-2 focus:ring-[#6b4b3e] outline-none">

        <button type="submit" class="w-full rounded-md bg-[#6b4b3e] hover:bg-[#b38963] transition text-white py-2 font-semibold">
          Masuk
        </button>
      </form>

      <p class="text-center text-sm mt-4 text-gray-700">
        Belum punya akun? <a href="register.php" class="text-blue-700 hover:underline">Daftar di sini</a>
      </p>
    </section>
  </main>
</body>
</html>