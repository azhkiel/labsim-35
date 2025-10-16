<?php
session_start();
include("config.php");

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validasi
    if (empty($username) || empty($new_password) || empty($confirm_password)) {
        $error_message = "Semua field harus diisi!";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "Password dan Confirm Password tidak cocok!";
    } elseif (strlen($new_password) < 6) {
        $error_message = "Password minimal 6 karakter!";
    } else {
        // Cek apakah username ada di database
        $check_query = "SELECT id_login FROM akun_login WHERE username = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE akun_login SET password = ? WHERE username = ?";
            $update_stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($update_stmt, "ss", $hashed_password, $username);
            
            if (mysqli_stmt_execute($update_stmt)) {
                $success_message = "Password berhasil diubah!";
            } else {
                $error_message = "Gagal mengubah password: " . mysqli_error($conn);
            }
            
            mysqli_stmt_close($update_stmt);
        } else {
            $error_message = "Username tidak ditemukan!";
        }
        
        mysqli_stmt_close($check_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Ganti Password | Gruduk Cafe</title>
</head>

<body class="relative min-h-screen backdrop-blur-sm bg-cover bg-center"
  style="background-image: url('assets/Background 1.jpg');">
  <!-- ===== NAVBAR ===== -->
  <header class="w-full bg-white/95 backdrop-blur shadow">
    <div class="max-w-7xl mx-auto flex items-center px-4 md:px-6 py-3">
      <!-- Brand kiri -->
      <a href="#" class="flex items-center gap-3">
        <img src="Assets/logo gruduk new.png" class="w-11 h-11 object-contain" alt="Gruduk Cafe">
        <div class="text-2xl font-extrabold tracking-wide">
          <span class="text-[#3b2f2a]">Gruduk</span>
          <span class="text-[#0c3e73]"> Cafe</span>
        </div>
      </a>

      <div class="flex-1"></div>

      <!-- Navbar Menu kanan -->
      <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-gray-700">
        <a id="homeLink" href="#" class="hover:text-[#6b4b3e] transition-colors duration-300">Home</a>
        <a id="aboutLink" href="#" class="hover:text-[#6b4b3e] transition-colors duration-300">About Us</a>
        <a id="teamLink" href="#" class="hover:text-[#6b4b3e] transition-colors duration-300">Our Team</a>
        <a id="contactLink" href="#" class="hover:text-[#6b4b3e] transition-colors duration-300">Contact Us</a>
        <a id="locationLink" href="#" class="hover:text-[#6b4b3e] transition-colors duration-300">Our Location</a>

        <!-- Foto Profil Maulana Afrizki di pojok kanan -->
        <div class="flex items-center ml-auto">
          <img src="assets/Maulana Afrizki.JPG" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300"
            alt="Profile">
        </div>
      </nav>
  </header>

  <script>
    // Fungsi untuk set link yang sesuai
    function setNavbarLinks() {
      const homeLink = document.getElementById("homeLink");
      const aboutLink = document.getElementById("aboutLink");
      const teamLink = document.getElementById("teamLink");
      const contactLink = document.getElementById("contactLink");
      const locationLink = document.getElementById("locationLink");

      // Kembali ke halaman index.html
      if (window.location.pathname.includes("index.php") || window.location.pathname === "/") {
        // Jika berada di halaman utama, link akan scroll ke section yang sesuai
        homeLink.setAttribute("href", "#home");
        aboutLink.setAttribute("href", "#about");
        teamLink.setAttribute("href", "#team");
        contactLink.setAttribute("href", "#contact");
        locationLink.setAttribute("href", "#location");
      } else {
        // Jika tidak berada di halaman utama, link akan kembali ke index.php
        homeLink.setAttribute("href", "index.php");
        aboutLink.setAttribute("href", "index.php#about");
        teamLink.setAttribute("href", "index.php#team");
        contactLink.setAttribute("href", "index.php#contact");
        locationLink.setAttribute("href", "index.php#location");
      }
    }

    // Panggil fungsi untuk set link navbar
    setNavbarLinks();
  </script>

  <!--Ganti Password-->
  <div class="flex items-center justify-center min-h-[calc(100vh-60px)]">
    <div class="bg-white bg-opacity-90 rounded-[40px] shadow-2xl w-[400px] px-10 py-8 flex flex-col items-center">
      <!-- Icon user -->
      <div class="mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500 mx-auto" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <circle cx="12" cy="8" r="4" />
          <path d="M6 20c0-2.2 3.6-3.3 6-3.3s6 1.1 6 3.3" />
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-black mb-6 tracking-wide text-center">New Password</h1>

      <!-- Notifikasi Error/Success -->
      <?php if ($error_message): ?>
        <div class="w-full mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
          <?php echo htmlspecialchars($error_message); ?>
        </div>
      <?php endif; ?>

      <?php if ($success_message): ?>
        <div class="w-full mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
          <?php echo htmlspecialchars($success_message); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="" class="w-full">
        <div class="flex justify-between items-center mb-1">
          <label class="text-gray-500 text-sm">Username</label>
        </div>
        <input type="text" name="username" placeholder="Masukkan username" required
          class="block w-full px-4 py-2 mt-2 text-gray-700 bg-gray-100 border rounded-lg focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" 
          value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />
        
        <div class="flex justify-between items-center mb-1 mt-4">
          <label class="text-gray-500 text-sm">New Password</label>
        </div>
        <div class="relative mb-4">
          <input type="password" name="new_password" placeholder="Masukkan password baru" required
            class="w-full px-4 py-2 bg-gray-100 rounded text-gray-700 border border-gray-300 pr-10 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
            id="passwordField">
          <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" onclick="togglePassword('passwordField')">
            <i class="far fa-eye text-gray-500"></i>
          </span>
        </div>

        <div class="flex justify-between items-center mb-1">
          <label class="text-gray-500 text-sm">Confirm Password</label>
        </div>
        <div class="relative mb-6">
          <input type="password" name="confirm_password" placeholder="Konfirmasi password baru" required
            class="w-full px-4 py-2 bg-gray-100 rounded text-gray-700 border border-gray-300 pr-10 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
            id="confirmPasswordField">
          <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" onclick="togglePassword('confirmPasswordField')">
            <i class="far fa-eye text-gray-500"></i>
          </span>
        </div>

        <button type="submit"
          class="w-full bg-yellow-900 text-white font-bold rounded py-2 mb-4 hover:bg-yellow-800 transition block text-center">
          Change Password
        </button>

        <a href="signin_as.php"
          class="w-full bg-gray-500 text-white font-bold rounded py-2 mb-4 hover:bg-gray-600 transition block text-center">
          Back to Sign In
        </a>
      </form>

      <!-- Icon Media Sosial -->
      <div class="flex justify-center gap-7 text-2xl text-gray-700 mt-2 mb-1">
        <a href="#"><i class="fab fa-whatsapp"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
        <a href="#"><i class="far fa-envelope"></i></a>
      </div>
    </div>
  </div>

  <script>
    // Password toggle function
    function togglePassword(fieldId) {
      const inp = document.getElementById(fieldId);
      const icon = inp.nextElementSibling.querySelector('i');
      
      if (inp.type === 'password') {
        inp.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        inp.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }

    // Real-time password validation
    document.addEventListener('DOMContentLoaded', function() {
      const passwordField = document.getElementById('passwordField');
      const confirmField = document.getElementById('confirmPasswordField');
      
      function validatePasswords() {
        if (passwordField.value && confirmField.value) {
          if (passwordField.value !== confirmField.value) {
            confirmField.classList.add('border-red-500');
            confirmField.classList.remove('border-green-500');
          } else {
            confirmField.classList.remove('border-red-500');
            confirmField.classList.add('border-green-500');
          }
        }
      }
      
      passwordField.addEventListener('input', validatePasswords);
      confirmField.addEventListener('input', validatePasswords);
    });
  </script>

</body>
</html>