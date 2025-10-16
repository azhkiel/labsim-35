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
        // Ambil user dari database
        $query = "SELECT al.*, da.nama, da.role AS user_role 
                  FROM akun_login al 
                  LEFT JOIN detail_akun da ON al.id_login = da.id_login 
                  WHERE al.username = '$username' 
                  LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Verifikasi password hash
            if (password_verify($password, $row['password'])) {
                // Set session
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
                        header("Location: dashboard karyawan.php");
                        break;
                    default:
                        header("Location: dashboard karyawan.php");
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
  <title>Login | Gruduk Cafe</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="relative min-h-screen bg-cover bg-center" style="background-image: url('assets/Background 1.jpg');">

  <!-- ===== NAVBAR ===== -->
  <header class="fixed top-0 left-0 w-full bg-white/95 backdrop-blur shadow z-20">
    <div class="max-w-7xl mx-auto flex items-center px-4 md:px-6 py-3">
      <!-- Brand -->
      <a href="#" class="flex items-center gap-3">
        <img src="Assets/logo gruduk new.png" class="w-11 h-11 object-contain" alt="Gruduk Cafe">
        <div class="text-2xl font-extrabold tracking-wide">
          <span class="text-[#3b2f2a]">Gruduk</span>
          <span class="text-[#0c3e73]"> Cafe</span>
        </div>
      </a>

      <div class="flex-1"></div>

      <!-- Menu -->
      <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-gray-700">
        <a href="index.php" class="hover:text-[#6b4b3e]">Home</a>
        <a href="index.php#about" class="hover:text-[#6b4b3e]">About Us</a>
        <a href="index.php#team" class="hover:text-[#6b4b3e]">Our Team</a>
        <a href="index.php#contact" class="hover:text-[#6b4b3e]">Contact Us</a>
        <a href="index.php#location" class="hover:text-[#6b4b3e]">Our Location</a>
      </nav>
    </div>
  </header>

  <!-- ===== FORM LOGIN ===== -->
  <main class="flex justify-center items-center pt-32 pb-16 px-4">
    <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
      <div class="flex flex-col items-center">
        <img class="w-auto h-24" src="assets/Logo Gruduk Cafe.png" alt="Logo Gruduk Cafe">
        <h2 class="text-3xl font-bold text-black text-center mt-2">Sign In</h2>
      </div>

      <?php if (!empty($error_message)) : ?>
        <div class="bg-red-100 text-red-700 p-3 mt-4 rounded-md text-center text-sm">
          <?= $error_message ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="" class="mt-6">
        <div>
          <label for="username" class="block text-sm text-gray-800">Username</label>
          <input type="text" id="username" name="username"
            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border rounded-lg focus:ring focus:ring-yellow-300 focus:outline-none" />
        </div>

        <div class="mt-4">
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm text-gray-800">Password</label>
          </div>
          <input type="password" id="password" name="password"
          class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border rounded-lg focus:ring focus:ring-yellow-300 focus:outline-none" />
          <a href="ganti password.php" class="text-xs text-gray-600 hover:underline">Forget Password?</a>
        </div>

        <div class="mt-6">
          <button type="submit"
            class="w-full px-6 py-2.5 text-sm font-medium text-white bg-yellow-600 rounded-lg hover:bg-yellow-800 focus:ring focus:ring-gray-300 focus:outline-none">
            Sign In
          </button>
        </div>
      </form>

      <p class="mt-8 text-xs font-light text-center text-gray-400">
        Don't have an account?
        <a href="register.php" class="font-medium text-gray-700 hover:underline">Create One</a>
      </p>
    </div>
  </main>

</body>
</html>