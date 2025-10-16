<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In | Gruduk Cafe</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen">

  <!-- ===== BACKGROUND ===== -->
  <div class="fixed inset-0 -z-10">
    <img src="Assets/Background 1.jpg" alt="" class="w-full h-full object-cover">
    <div class="absolute inset-0 backdrop-blur-[3px]"></div>
  </div>

  <!-- ===== NAVBAR ===== -->
  <header class="w-full bg-[#d4c29a] shadow">
    <div class="max-w-7xl mx-auto flex items-center px-4 md:px-6 py-2.5">
      <!-- Brand kiri (klik logo kembali ke index) -->
      <a id="brandLink" href="index.php" class="flex items-center gap-3">
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
        <div class="flex items-center ml-auto">
          <img src="Assets/Fahras Zeilicha.jpg" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300"
            alt="Profile">
        </div>
      </nav>
    </div>
  </header>

  <script>
    // Set link navbar & brand agar kembali ke index
    function setNavbarLinks() {
      const brandLink = document.getElementById("brandLink");
      const homeLink = document.getElementById("homeLink");
      const aboutLink = document.getElementById("aboutLink");
      const teamLink = document.getElementById("teamLink");
      const contactLink = document.getElementById("contactLink");
      const locationLink = document.getElementById("locationLink");

      const onHome = window.location.pathname.includes("index.php")
        || window.location.pathname === "/";

      // brand/logo -> ke beranda index
      brandLink.setAttribute("href", onHome ? "#home" : "index.php#home");

      // link navbar
      homeLink.setAttribute("href", onHome ? "#home" : "index.php#home");
      aboutLink.setAttribute("href", onHome ? "#about" : "index.php#about");
      teamLink.setAttribute("href", onHome ? "#team" : "index.php#team");
      contactLink.setAttribute("href", onHome ? "#contact" : "index.php#contact");
      locationLink.setAttribute("href", onHome ? "#location" : "index.php#location");
    }
    setNavbarLinks();
  </script>

  <!-- ===== CARD ===== -->
  <main class="px-4">
    <section
      class="max-w-3xl mx-auto mt-24 md:mt-28 rounded-xl border border-white/50 bg-white/55 backdrop-blur-md shadow-[0_20px_60px_-20px_rgba(0,0,0,0.45)]">
      <div class="relative px-6 md:px-16 py-10 md:py-14 text-center">

        <!-- ikon user pojok kanan -->
        <div class="absolute top-3 right-3 md:top-4 md:right-4 text-gray-700/80">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>

        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900">Sign In As</h1>
        <!-- tombol -->
        <div class="mt-8 md:mt-10 flex items-center justify-center gap-10 md:gap-16">
          <!-- Staff -->
          <a href="login.php"
            class="cursor-pointer inline-block rounded-full bg-[#6b4b3e] hover:bg-[#b38963] text-white font-extrabold tracking-wide px-20 py-3 shadow transition">
            STAFF
          </a>

          <!-- HRD -->
          <a href="hrd_signin.php"
            class="cursor-pointer inline-block rounded-full bg-[#6b4b3e] hover:bg-[#b38963] text-white font-extrabold tracking-wide px-20 py-3 shadow transition">
            HRD
          </a>
        </div>

        <!-- ruang bawah agar kartu terasa tinggi seperti contoh -->
        <div class="h-10 md:h-12"></div>
      </div>
    </section>
  </main>

</body>

</html>