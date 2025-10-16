<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>Index | Gruduk Cafe</title>
</head>

<body class="relative min-h-screen backdrop-blur-sm bg-cover bg-center"
  style="background-image:url('assets/Background 1.jpg');">

  <!-- ===== NAVBAR ===== -->
  <header class="w-full bg-white/95 backdrop-blur shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex items-center px-4 md:px-6 py-3">
      <!-- Brand kiri -->
      <a href="#home" class="flex items-center gap-3">
        <img src="Assets/logo gruduk new.png" class="w-11 h-11 object-contain" alt="Gruduk Cafe">
        <div class="text-2xl font-extrabold tracking-wide">
          <span class="text-[#3b2f2a]">Gruduk</span>
          <span class="text-[#0c3e73]"> Cafe</span>
        </div>
      </a>

      <div class="flex-1"></div>

      <!-- Menu kanan -->
      <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-gray-700">
        <a href="#home" class="hover:text-[#6b4b3e]">Home</a>
        <a href="#about" class="hover:text-[#6b4b3e]">About Us</a>
        <a href="#team" class="hover:text-[#6b4b3e]">Our Team</a>
        <a href="#contact" class="hover:text-[#6b4b3e]">Contact Us</a>
        <a href="#location" class="hover:text-[#6b4b3e]">Our Location</a>
      </nav>
    </div>
  </header>

  <!-- ===== HEROES ===== -->
  <section id="home" class="relative bg-cover bg-center h-[500px]">
    <!-- Overlay transparan -->
    <div class="absolute inset-0"></div>

    <div class="container mx-auto relative z-10 flex items-center justify-center h-full">
      <!-- Card Transparan -->
      <div class="bg-white/70 backdrop-blur-sm p-22 rounded-lg shadow-lg flex items-center space-x-6 max-w-6xl">
        <!-- Gambar Kopi -->
        <div class="flex-shrink-0">
          <img src="assets/Background 2.png" alt="Kopi"
            class="w-32 h-32 object-cover rounded-full border-4 border-white shadow-md">
        </div>

        <!-- Konten Teks -->
        <div>
          <h2 class="text-5xl font-extrabold text-yellow-900 mb-4 italic text-center">WELCOME!</h2>
          <p class="text-gray-700 mb-4 ml-5 text-center">
            Gruduk Cafe hadir untuk mewarnai harimu. <br>
            Kopi tidak perlu mahal untuk jadi spesial
          </p>
          <!-- Tombol -->
          <div class="flex space-x-4">
            <a href="login.php"
              class="px-10 py-2 ml-5 bg-yellow-900 text-white font-semibold rounded-full shadow hover:bg-yellow-700 transition">SIGN
              IN</a>
            <a href="register.php"
              class="px-10 py-2 ml-5 bg-gray-700 text-white font-semibold rounded-full shadow hover:bg-gray-600 transition">REGISTER</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Ikon Sosial Media -->
    <div class="absolute left-10 top-1/2 flex flex-col space-y-4 z-10">
    <a href="#"><img src="assets/facebook.png" class="h-8 w-8" alt="Facebook"></a>
    <a href="#"><img src="assets/instagram.png" class="h-8 w-8" alt="Instagram"></a>
    <a href="#"><img src="assets/whatsapp.png" class="h-8 w-8" alt="WhatsApp"></a>
</div>
  </section>

  <!-- ===== ABOUT ===== -->
  <section id="about" class="bg-[#5c3d2e] py-5">
    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-lg p-8">
      <!-- Judul -->
      <h2 class="text-3xl font-bold text-center text-[#5c3d2e] mb-4">
        About Gruduk Cafe
      </h2>

      <!-- Deskripsi -->
      <p class="text-center text-gray-700 leading-relaxed mb-8">
        Gruduk Cafe adalah tempat yang menghadirkan pengalaman ngopi dengan kualitas terbaik namun tetap terjangkau.
        Kami percaya bahwa kopi tidak perlu mahal untuk menjadi spesial. <br><br>
        Dengan biji kopi pilihan, proses penyajian yang penuh ketelitian, serta suasana hangat dan ramah,
        Gruduk Cafe hadir untuk menemani setiap momenmu — mulai dari santai bersama teman, bekerja, hingga sekadar
        me-time.
      </p>

      <!-- Visi & Misi -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Visi -->
        <div class="bg-[#5c3d2e] text-white rounded-lg p-6 shadow-md">
          <h3 class="text-xl font-bold mb-2">Our Visi</h3>
          <p class="text-sm leading-relaxed">
            Menjadi kafe pilihan utama yang dikenal karena kualitas kopi terbaik dengan harga terjangkau,
            serta mampu menciptakan suasana nyaman untuk semua kalangan.
          </p>
        </div>

        <!-- Misi -->
        <div class="bg-[#5c3d2e] text-white rounded-lg p-6 shadow-md">
          <h3 class="text-xl font-bold mb-2">Our Misi</h3>
          <ol class="list-decimal list-inside text-sm leading-relaxed space-y-1">
            <li>Menyajikan kopi berkualitas tinggi dari biji pilihan dengan harga yang bersahabat.</li>
            <li>Memberikan pelayanan ramah dan hangat agar setiap pengunjung merasa seperti di rumah sendiri.</li>
            <li>Menciptakan suasana kafe yang nyaman untuk berbagai aktivitas: bersantai, bekerja, maupun berkumpul.
            </li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== OUR TEAM ===== -->
  <section id="team" class="bg-white dark:bg-gray-900">
    <div class="container px-6 py-10 mx-auto">
      <h1 class="text-2xl font-semibold text-center text-gray-800 capitalize lg:text-3xl dark:text-white">
        Our Operational Management Team
      </h1>

      <p class="max-w-2xl mx-auto my-6 text-center text-gray-500 dark:text-gray-300">
        Dengan bangga kami memperkenalkan orang-orang hebat beserta jabatan yang dipegang saat ini.
      </p>

      <div class="grid grid-cols-1 gap-8 mt-8 xl:mt-16 md:grid-cols-2 xl:grid-cols-4">
        <!-- Card 1 -->
        <div
          class="flex flex-col items-center p-8 transition-colors duration-300 transform border cursor-pointer rounded-xl hover:border-transparent group hover:bg-yellow-800 dark:border-gray-700 dark:hover:border-transparent">
          <img class="object-cover w-32 h-32 rounded-full ring-4 ring-gray-300" src="assets/Fahras Zeilicha.jpg"
            alt="Fahras Zeilicha">
          <h1 class="mt-4 text-2xl font-semibold text-gray-700 capitalize dark:text-white group-hover:text-white">Fahras
            Zeilicha</h1>
          <p class="mt-2 text-gray-500 capitalize dark:text-gray-300 group-hover:text-gray-300">HRD</p>
          <!-- socials ... (dipertahankan) -->
        </div>

        <!-- Card 2 -->
        <div
          class="flex flex-col items-center p-8 transition-colors duration-300 transform border cursor-pointer rounded-xl hover:border-transparent group hover:bg-yellow-800 dark:border-gray-700 dark:hover:border-transparent">
          <img class="object-cover w-32 h-32 rounded-full ring-4 ring-gray-300" src="assets/Maulana Afrizki.JPG"
            alt="Maulana Afrizki">
          <h1 class="mt-4 text-2xl font-semibold text-gray-700 capitalize dark:text-white group-hover:text-white">
            Maulana Afrizki</h1>
          <p class="mt-2 text-gray-500 capitalize dark:text-gray-300 group-hover:text-gray-300">Store Manager</p>
        </div>

        <!-- Card 3 -->
        <div
          class="flex flex-col items-center p-8 transition-colors duration-300 transform border cursor-pointer rounded-xl hover:border-transparent group hover:bg-yellow-800 dark:border-gray-700 dark:hover:border-transparent">
          <img class="object-cover w-32 h-32 rounded-full ring-4 ring-gray-300" src="assets/Sendy Prayoga.jpg"
            alt="Sendy Prayoga">
          <h1 class="mt-4 text-2xl font-semibold text-gray-700 capitalize dark:text-white group-hover:text-white">Sendy
            Prayoga</h1>
          <p class="mt-2 text-gray-500 capitalize dark:text-gray-300 group-hover:text-gray-300">Supervisor</p>
        </div>

        <!-- Card 4 -->
        <div
          class="flex flex-col items-center p-8 transition-colors duration-300 transform border cursor-pointer rounded-xl hover:border-transparent group hover:bg-yellow-800 dark:border-gray-700 dark:hover:border-transparent">
          <img class="object-cover w-32 h-32 rounded-full ring-4 ring-gray-300" src="assets/Rendy Prasetyo.jpg"
            alt="Rendy Prasetyo">
          <h1 class="mt-4 text-2xl font-semibold text-gray-700 capitalize dark:text-white group-hover:text-white">Rendy
            Prasetyo</h1>
          <p class="mt-2 text-gray-500 capitalize dark:text-gray-300 group-hover:text-gray-300">Frontliner Staff</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== CONTACT US ===== -->
  <section id="contact" class="relative">
    <!-- Logo Gruduk Cafe (kanan atas, opsional) -->
    <div class="absolute top-4 right-4">
      <img src="assets/Logo Gruduk Cafe.png" alt="Logo Gruduk Cafe" class="h-12 w-12 rounded-full shadow-lg" />
    </div>

    <!-- Social Media-->
    <div class="absolute top-4 left-4 flex gap-3 text-white text-xl">
      <a href="#"><i class="fab fa-whatsapp"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
      <a href="#"><i class="far fa-envelope"></i></a>
      <a href="#"><i class="fab fa-youtube"></i></a>
    </div>

    <!-- Contact Info Box -->
    <div class="flex flex-col items-center justify-center min-h-screen">
      <h1 class="text-white text-4xl font-bold mb-8 mt-10 drop-shadow-lg">Our Contact Info</h1>
      <div class="bg-white bg-opacity-90 rounded-lg p-8 shadow-lg w-96">
        <!-- Address -->
        <div class="mb-4">
          <div class="bg-yellow-800 font-semibold rounded-t-lg px-4 py-2 text-center text-white">Address</div>
          <div class="border-x border-b border-black px-4 py-2 text-center text-gray-700">
            Jalan Jendral Wito No 45,<br>Jakarta Pusat Indonesia
          </div>
        </div>
        <!-- Mail -->
        <div class="mb-4">
          <div class="bg-yellow-800 font-semibold rounded-t-lg px-4 py-2 text-center text-white">Mail</div>
          <div class="border-x border-b border-black px-4 py-2 text-center text-gray-700">
            <a href="mailto:waktunyagruduk@gmail.com" class="text-blue-500 hover:underline">waktunyagruduk@gmail.com</a>
          </div>
        </div>
        <!-- Phone Number -->
        <div class="mb-4">
          <div class="bg-yellow-800 font-semibold rounded-t-lg px-4 py-2 text-center text-white">Phone Number</div>
          <div class="border-x border-b border-black px-4 py-2 text-center text-gray-700">
            +62 811 4476 7863
          </div>
        </div>
        <!-- Instagram -->
        <div class="mb-4">
          <div class="bg-yellow-800 font-semibold rounded-t-lg px-4 py-2 text-center text-white">Instagram</div>
          <div class="border-x border-b border-black px-4 py-2 text-center text-gray-700">
            @grudukcafe1976
          </div>
        </div>
        <!-- YouTube -->
        <div>
          <div class="bg-yellow-800 font-semibold rounded-t-lg px-4 py-2 text-center text-white">YouTube</div>
          <div class="border-x border-b border-black px-4 py-2 text-center text-gray-700">
            Waktunya Gruduk
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== LOCATION (opsional; agar link navbar bekerja) ===== -->
  <section id="location" class="bg-white py-12">
    <div class="max-w-5xl mx-auto px-4">
      <h2 class="text-3xl font-bold text-center text-[#5c3d2e] mb-6">Our Location</h2>
      <!-- Jika ada embed maps, taruh di sini -->
      <div class="aspect-video w-full rounded-lg overflow-hidden shadow">
        <!-- Ganti src map di bawah sesuai alamat kafe -->
        <iframe class="w-full h-full" src="https://www.google.com/maps?q=Jakarta%20Pusat&output=embed" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</body>

</html>