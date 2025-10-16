<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>Upload Bukti Absen | Gruduk Cafe</title>
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

  <div class="flex min-h-[calc(100vh-60px)]">
    <!-- ===== SIDEBAR ===== -->
    <aside class="w-64 bg-white/80 flex flex-col p-6 shadow-lg">

      <!-- Dashboard -->
      <a href="dashboard karyawan.php"
        class="flex items-center w-full bg-white text-yellow-900 border border-yellow-900 rounded px-3 py-2 mb-3 gap-3 font-bold hover:bg-yellow-900 hover:text-white transition">
        <span><!-- icon -->&#9776;</span>
        Dashboard
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

      <!-- Cuti / Izin -->
      <a href="cuti izin karyawan.php"
        class="flex items-center w-full bg-white text-yellow-900 border border-yellow-900 rounded px-3 py-2 my-3 gap-3 font-semibold hover:bg-yellow-900 hover:text-white transition">
        Cuti / Izin
      </a>

      <!-- Biodata -->
      <a href="biodata karyawan.php"
        class="flex items-center w-full bg-yellow-900 text-white border border-yellow-900 rounded px-3 py-2 gap-3 font-semibold hover:bg-yellow-500 hover:text-white transition">
        Biodata
      </a>

      <!-- Logout -->
      <a href="signin_as.php"
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
      // *ganti sesuai routingmu (hash atau file terpisah)
      const shouldOpen =
        location.hash === '#riwayat' ||
        /riwayat-(kehadiran|cuti)/i.test(location.pathname);
      if (shouldOpen) expand();

      // Optional: tutup dropdown lain kalau ada lebih dari satu grup
      // (tinggal cari semua .submenu lalu collapse yang bukan ini)
    </script>

    <!-- Main content -->
    <main class="flex-1 flex items-center justify-center">
      <div class="flex items-center justify-center min-h-[calc(100vh-60px)]">
        <div class="bg-white bg-opacity-90 rounded-[40px] shadow-2xl w-[400px] px-10 py-8 flex flex-col items-center">
          <div class="text-3xl font-bold text-yellow-900 mb-6 italic">Upload Bukti Absen</div>

          <!-- File Upload Section -->
          <div class="w-full max-w-sm bg-gray-100 border-2 border-gray-300 p-4 rounded-lg">
            <label for="fileInput" class="block text-gray-700 text-lg font-semibold mb-2">Pilih Bukti Absen
              (Foto)</label>
            <input type="file" id="fileInput" class="w-full border-2 border-gray-300 rounded-lg p-2 text-gray-700"
              onchange="previewImage(event)">
            <div id="imagePreview" class="mt-4 text-gray-500">Preview image will appear here</div>
          </div>

          <!-- Upload Button -->
          <button onclick="submitAbsen()"
            class="bg-yellow-900 text-white px-10 py-2 rounded-full font-bold shadow-md hover:bg-yellow-800 transition mt-6">
            Upload
          </button>
        </div>
    </main>
  </div>

  <script>
    // Function to preview the uploaded image
    function previewImage(event) {
      const file = event.target.files[0];
      const reader = new FileReader();

      reader.onload = function (e) {
        // Create an image element to preview
        const img = document.createElement('img');
        img.src = e.target.result;
        img.classList.add('rounded-lg', 'w-full', 'h-auto', 'max-h-64', 'object-contain');

        const previewDiv = document.getElementById('imagePreview');
        previewDiv.innerHTML = ''; // Clear previous preview
        previewDiv.appendChild(img); // Append new image preview
      };

      if (file) {
        reader.readAsDataURL(file); // Read the file and preview it
      }
    }

    // Function to handle the "Upload" button click
    function submitAbsen() {
      alert('Bukti Absen telah di-upload!');
    }
  </script>

</body>

</html>