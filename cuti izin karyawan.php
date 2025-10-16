<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>Cuti / Izin Karyawan | Gruduk Cafe</title>
</head>

<body class="relative min-h-screen backdrop-blur-sm bg-cover bg-center"
  style="background-image: url('assets/Background 1.jpg');">

  <!-- ===== LAYOUT ===== -->
  <!-- Pakai min-h-screen agar frame penuh layar -->
  <div class="flex min-h-screen">

    <!-- ===== SIDEBAR (full height) ===== -->
    <aside class="w-64 min-h-screen bg-white/80 flex flex-col p-6 shadow-lg">

      <!-- Dashboard -->
      <a href="dashboard karyawan.php"
        class="flex items-center w-full bg-white text-yellow-900 border border-yellow-900 px-3 py-2 gap-3 font-bold hover:bg-yellow-500 hover:text-white transition">
        <span aria-hidden="true">&#9776;</span>
        <span>Dashboard</span>
      </a>

      <!-- Cuti / Izin (current) -->
      <a href="cuti izin karyawan.php"
        class="flex items-center w-full bg-yellow-900 text-white border border-white rounded px-3 py-2 my-3 gap-3 font-semibold hover:bg-yellow-800 transition">
        Cuti / Izin
      </a>

      <!-- ===== RIWAYAT (dropdown) ===== -->
      <button id="btnRiwayat" aria-expanded="false" aria-controls="submenuRiwayat"
        class="flex items-center justify-between w-full bg-white text-yellow-900 border border-yellow-900 rounded px-3 py-2 mb-2 font-semibold hover:bg-yellow-900 hover:text-white transition focus:outline-none focus:ring-2 focus:ring-yellow-900/40">
        <span class="flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 24 24" fill="none"
            stroke="currentColor">
            <circle cx="12" cy="12" r="10" />
            <path d="M14 10l-4 4m0-4l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Riwayat
        </span>
        <svg id="chevRiwayat" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-300"
          viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd"
            d="M5.23 7.21a.75.75 0 011.06.02L10 11.06l3.71-3.83a.75.75 0 111.08 1.04l-4.24 4.38a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
            clip-rule="evenodd" />
        </svg>
      </button>

      <!-- Submenu -->
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
        class="flex items-center w-full bg-white text-yellow-900 border border-yello-900 rounded px-3 py-2 gap-3 font-semibold hover:bg-yellow-900 hover:text-white transition">
        Biodata
      </a>

      <!-- Logout -->
      <a href="signin_as.php"
        class="mt-auto w-full text-yellow-900 border border-yellow-900 rounded px-3 py-2 font-bold bg-white hover:bg-yellow-900 hover:text-white transition">
        Log out
      </a>
    </aside>

    <!-- ===== MAIN ===== -->
    <main class="flex-1 flex items-start justify-center p-6">
      <div class="relative w-full max-w-4xl">
        <!-- WATERMARK -->
        <img src="assets/Logo Gruduk Cafe.png" alt="" aria-hidden="true"
          class="pointer-events-none select-none absolute inset-0 m-auto w-64 opacity-10 -z-10" />

        <!-- PANEL -->
        <div class="relative z-10 bg-white/80 rounded-lg shadow-lg pt-3 pb-8 px-8 text-center">
          <h1 class="text-3xl font-bold text-yellow-900 mb-4 mt-1">CUTI / IZIN</h1>
          <!-- Form -->
          <!-- Form -->
          <form class="grid grid-cols-4 gap-2 mb-6 items-center" 
                action="proses_cuti.php" method="POST" enctype="multipart/form-data">
            <div>
              <input type="date" name="tanggal_mulai" required
                class="w-full rounded-lg border border-yellow-900 bg-yellow-100 px-3 py-2 font-semibold focus:bg-yellow-200 focus:outline-none">
            </div>

            <div>
              <input type="date" name="tanggal_akhir" required
                class="w-full rounded-lg border border-yellow-900 bg-yellow-100 px-3 py-2 font-semibold focus:bg-yellow-200 focus:outline-none">
            </div>

            <div>
              <select name="tipe" required
                class="w-full rounded-lg border border-yellow-900 bg-yellow-100 px-3 py-2 font-semibold focus:bg-yellow-200 focus:outline-none">
                <option value="" disabled selected>Option</option>
                <option value="izin">Izin</option>
                <option value="cuti">Cuti</option>
                <option value="sakit">Sakit</option>
              </select>
            </div>

            <div>
              <input type="text" name="keterangan" placeholder="Keterangan..." required
                class="w-full rounded-lg border border-yellow-900 bg-yellow-100 px-3 py-2 font-semibold focus:bg-yellow-200 focus:outline-none">
            </div>
            <div class="col-span-4 flex justify-center mt-4">
              <button type="submit"
                class="w-48 text-center text-yellow-900 border border-yellow-900 rounded px-3 py-2 font-bold bg-white hover:bg-yellow-900 hover:text-white transition">
                SUBMIT
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <!-- ===== Dropdown Script (tunggal, tanpa duplikasi) ===== -->
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
    btn.addEventListener('click', () => {
      const expanded = btn.getAttribute('aria-expanded') === 'true';
      expanded ? collapse() : expand();
    });

    // Otomatis buka dropdown saat berada di halaman riwayat*
    const shouldOpen = location.hash === '#riwayat' || /riwayat-(kehadiran|cuti)/i.test(location.pathname);
    if (shouldOpen) expand();
  </script>
</body>

</html>