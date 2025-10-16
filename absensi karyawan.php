<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>Absensi Karyawan | Gruduk Cafe</title>
</head>

<body class="relative min-h-screen backdrop-blur-sm bg-cover bg-center"
  style="background-image: url('assets/Background\ 1.jpg');">
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
        // Jika tidak berada di halaman utama, link akan kembali ke index.html
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
  </div>
  </header>

  <!--Isi Absensi-->
  <div class="flex items-center justify-center min-h-[calc(100vh-60px)]">
    <div class="bg-white bg-opacity-90 rounded-[40px] shadow-2xl w-[400px] px-10 py-8 flex flex-col items-center">
      <!-- Icon centang -->
      <div class="mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-black" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <rect x="2" y="2" width="20" height="20" rx="6" stroke="currentColor" stroke-width="2" fill="none" />
          <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            d="M8 12l3 3 5-5" />
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-black mb-6 tracking-wide text-center">ABSENSI</h1>

      <form class="w-full">
        <div class="flex justify-between items-center mb-1">
          <label class="text-gray-500 text-sm">Nama Lengkap</label>
        </div>
        <input type="text"
          class="block w-full px-4 py-2 mt-2 text-gray-700 bg-gray-100 border rounded-lg focus:border-blue-400 focus:ring-blue-300 focus:outline-none" />

        <div class="flex justify-between items-center mb-1">
          <label class="text-gray-500 text-sm">id</label>
        </div>
        <input type="text"
          class="block w-full px-4 py-2 mt-2 text-gray-700 bg-gray-100 border rounded-lg focus:border-blue-400 focus:ring-blue-300 focus:outline-none" />

        <div class="flex justify-between items-center mb-1">
          <label class="text-gray-500 text-sm">Foto Bukti</label>
        </div>
        <label for="foto"
          class="block w-full bg-gray-200 px-3 py-2 rounded border border-gray-300 text-gray-700 mb-6 cursor-pointer">
          <span class="block text-center">Upload Foto</span>
          <input id="foto" type="file" class="hidden">
        </label>

        <!-- Tombol Submit yang mengarahkan ke Riwayat Kehadiran -->
        <a href="riwayat kehadiran.php" target="iframeRiwayat">
          <button type="button"
            class="w-full bg-yellow-900 text-white font-bold rounded py-2 mt-2 hover:bg-yellow-800 transition">
            SUBMIT
          </button>
        </a>
      </form>

</body>
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</html>