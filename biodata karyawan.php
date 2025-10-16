<?php
include("config.php");
session_start();

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

// Handle upload foto profil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['foto_profile'])) {
    if ($_FILES['foto_profile']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/fotoProfile/";
        
        // Buat folder jika belum ada
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES["foto_profile"]["name"], PATHINFO_EXTENSION);
        $filename = "profile_" . $karyawan['id_detail'] . "_" . date("Y-m-d_H-i-s") . "." . $file_extension;
        $target_file = $target_dir . $filename;
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["foto_profile"]["tmp_name"]);
        if($check !== false) {
            // Check file size (max 2MB)
            if ($_FILES["foto_profile"]["size"] > 2000000) {
                $error_message = "Ukuran file terlalu besar. Maksimal 2MB.";
            } else {
                // Allow certain file formats
                $allowed_extensions = ["jpg", "jpeg", "png", "gif"];
                if (in_array(strtolower($file_extension), $allowed_extensions)) {
                    if (move_uploaded_file($_FILES["foto_profile"]["tmp_name"], $target_file)) {
                        // Update database dengan foto profil baru
                        $update_query = "UPDATE detail_akun SET foto_profile = '$filename' WHERE id_detail = '" . $karyawan['id_detail'] . "'";
                        if (mysqli_query($conn, $update_query)) {
                            $success_message = "Foto profil berhasil diupdate!";
                            // Refresh data
                            $karyawan['foto_profile'] = $filename;
                        } else {
                            $error_message = "Gagal menyimpan data foto profil.";
                        }
                    } else {
                        $error_message = "Maaf, terjadi error saat upload file.";
                    }
                } else {
                    $error_message = "Hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
                }
            }
        } else {
            $error_message = "File bukan gambar.";
        }
    } elseif ($_FILES['foto_profile']['error'] !== UPLOAD_ERR_NO_FILE) {
        $error_message = "Error upload file: " . $_FILES['foto_profile']['error'];
    }
}

// Handle ganti password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validasi
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $password_error = "Semua field password harus diisi.";
    } elseif ($new_password !== $confirm_password) {
        $password_error = "Password baru dan konfirmasi password tidak cocok.";
    } elseif (strlen($new_password) < 6) {
        $password_error = "Password baru minimal 6 karakter.";
    } else {
        // Verifikasi password saat ini
        $check_password_query = "SELECT password FROM akun_login WHERE id_login = '$id_login'";
        $result = mysqli_query($conn, $check_password_query);
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($current_password, $user['password'])) {
            // Update password baru
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password_query = "UPDATE akun_login SET password = '$new_password_hash' WHERE id_login = '$id_login'";
            
            if (mysqli_query($conn, $update_password_query)) {
                $password_success = "Password berhasil diubah!";
            } else {
                $password_error = "Gagal mengubah password: " . mysqli_error($conn);
            }
        } else {
            $password_error = "Password saat ini salah.";
        }
    }
}

// Handle hapus akun
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    // Hapus data dari database
    $delete_query = "DELETE FROM detail_akun WHERE id_detail = '" . $karyawan['id_detail'] . "'";
    $delete_login_query = "DELETE FROM akun_login WHERE id_login = '$id_login'";
    
    if (mysqli_query($conn, $delete_query) && mysqli_query($conn, $delete_login_query)) {
        session_destroy();
        header("Location: login.php");
        exit;
    } else {
        $error_message = "Gagal menghapus akun.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>Biodata Karyawan | Gruduk Cafe</title>
</head>

<body class="relative min-h-screen backdrop-blur-sm bg-cover bg-center"
  style="background-image: url('assets/Background\ 1.jpg');">
  <div class="flex min-h-[calc(100vh-60px)]">
    <!-- ===== SIDEBAR ===== -->
    <aside class="w-64 bg-white/80 flex flex-col p-6 shadow-lg min-h-screen">

      <!-- Dashboard -->
      <a href="dashboard karyawan.php"
        class="flex items-center w-full bg-white text-yellow-900 border border-yellow-900 rounded px-3 py-2 gap-3 font-bold hover:bg-yellow-900 hover:text-white transition">
        <span>&#9776;</span>
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
        class="flex items-center w-full bg-yellow-900 text-white border border-yellow-900 rounded px-3 py-2 gap-3 font-semibold hover:bg-yellow-500 hover:text-white transition">
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

      btn.addEventListener('click', () => {
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        expanded ? collapse() : expand();
      });

      const shouldOpen =
        location.hash === '#riwayat' ||
        /riwayat-(kehadiran|cuti)/i.test(location.pathname);
      if (shouldOpen) expand();
    </script>

    <!-- Isi Biodata -->
    <main class="flex-1 flex items-center justify-center p-4">
      <div
        class="bg-white bg-opacity-80 rounded-lg shadow-lg py-6 px-8 w-full max-w-4xl text-center relative flex flex-col items-center">

        <div class="text-3xl font-bold text-yellow-900 mb-6 italic">BIODATA</div>
        <form method="POST" action="" enctype="multipart/form-data" class="flex flex-col items-center gap-6 z-10 w-full">
          <!-- Foto Karyawan dan Ganti Profil Button berada dalam satu kolom -->
          <div class="flex flex-col items-center gap-4">
            <!-- Foto Karyawan -->
            <div
              class="rounded-xl overflow-hidden shadow-lg border border-gray-300 w-[175px] h-[200px] bg-gray-100 flex items-center justify-center relative">
              <!-- Foto Profil yang dapat dipilih -->
              <img id="profileImage" 
                   src="<?php 
                         if (!empty($karyawan['foto_profile'])) {
                             echo 'uploads/fotoProfile/' . htmlspecialchars($karyawan['foto_profile']);
                         } else {
                             echo 'assets/pp.png';
                         }
                   ?>" 
                   class="w-full h-full object-cover"
                   alt="Foto Karyawan">

              <!-- Input file untuk ganti profil -->
              <input type="file" id="fileInput" name="foto_profile" accept="image/*" 
                     class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                     onchange="previewImage(event)">
            </div>
            <button type="button" onclick="document.getElementById('fileInput').click()"
              class="px-6 py-2 bg-yellow-900 text-white rounded hover:bg-yellow-700 transition block text-center">
              Klik Di Atas Untuk Ganti Profil
            </button>
          </div>

          <!-- Data Biodata -->
          <div class="bg-transparent flex items-center w-full justify-center">
            <ul class="grid grid-cols-2 gap-x-4 text-left text-base font-medium text-gray-800 leading-6 w-full">
              <li class="">
                <span class="font-semibold">Nama :</span>
                <span><?= htmlspecialchars($karyawan['nama'] ?? 'Maulana Afrizki') ?></span>
              </li>
              <li class="">
                <span class="font-semibold">Alamat :</span>
                <span><?= htmlspecialchars($karyawan['alamat'] ?? 'Perumahan Sidoarjo Indah No 7, Sidoarjo') ?></span>
              </li>
              <li class="">
                <span class="font-semibold">TTL :</span>
                <span>
                  <?= htmlspecialchars($karyawan['tempat_lahir'] ?? 'Jakarta') ?>, 
                  <?= isset($karyawan['tanggal_lahir']) ? date('d F Y', strtotime($karyawan['tanggal_lahir'])) : '30 Desember 2009' ?>
                </span>
              </li>
              <li class="">
                <span class="font-semibold">Jenis Kelamin :</span>
                <span><?= htmlspecialchars($karyawan['jenis_kelamin'] ?? 'Laki-Laki') ?></span>
              </li>
              <li class="">
                <span class="font-semibold">Agama :</span>
                <span><?= htmlspecialchars($karyawan['agama'] ?? 'Islam') ?></span>
              </li>
              <li class="">
                <span class="font-semibold">Email :</span>
                <span><?= htmlspecialchars($karyawan['email'] ?? 'mafrizki@gmail.com') ?></span>
              </li>
              <li class="">
                <span class="font-semibold">Departemen :</span>
                <span><?= htmlspecialchars($karyawan['departemen'] ?? 'Store Manager') ?></span>
              </li>
              <li class="">
                <span class="font-semibold">Jabatan :</span>
                <span><?= htmlspecialchars($karyawan['jabatan'] ?? 'Kepala Departemen') ?></span>
              </li>
              <li class="">
                <span class="font-semibold">Status :</span>
                <span><?= htmlspecialchars($karyawan['status'] ?? 'Aktif') ?></span>
              </li>
              <li class="">
                <span class="font-semibold">ID Karyawan :</span>
                <span><?= htmlspecialchars($karyawan['id_detail'] ?? 'ID') ?></span>
              </li>
            </ul>
          </div>

          <!-- Tombol Ganti Password dan Hapus Akun -->
          <div class="mt-6 flex gap-4">
            <button type="button" onclick="showPasswordModal()"
              class="px-6 py-2 bg-yellow-900 text-white rounded hover:bg-yellow-700 transition block text-center w-48">
              Ganti Password
            </button>

            <button type="button"
              class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition block text-center w-48"
              onclick="showDeleteConfirmation()">
              Hapus Akun
            </button>
          </div>
        </form>
      </div>
    </main>

    <!-- Modal Ganti Password -->
    <div id="passwordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-bold text-gray-800">Ganti Password</h3>
          <button type="button" onclick="hidePasswordModal()" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <?php if (isset($password_error)): ?>
          <div class="p-3 mb-4 bg-red-100 text-red-700 rounded-lg border border-red-300 text-sm">
            <?php echo $password_error; ?>
          </div>
        <?php endif; ?>

        <?php if (isset($password_success)): ?>
          <div class="p-3 mb-4 bg-green-100 text-green-700 rounded-lg border border-green-300 text-sm">
            <?php echo $password_success; ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
              <input type="password" name="current_password" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-900 focus:border-transparent">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
              <input type="password" name="new_password" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-900 focus:border-transparent">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
              <input type="password" name="confirm_password" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-900 focus:border-transparent">
            </div>
          </div>

          <div class="flex gap-3 justify-end mt-6">
            <button type="button" onclick="hidePasswordModal()"
              class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition">
              Batal
            </button>
            <button type="submit" name="change_password"
              class="px-4 py-2 bg-yellow-900 text-white rounded-md hover:bg-yellow-700 transition">
              Ganti Password
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Konfirmasi Hapus Akun -->
    <div id="deleteConfirmation"
      class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-xl text-center w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Apakah Anda yakin ingin menghapus akun?</h3>
        <p class="text-sm text-gray-600 mb-4">Tindakan ini tidak dapat dibatalkan!</p>
        <div class="flex gap-4 justify-center">
          <form method="POST" action="" class="inline">
            <input type="hidden" name="delete_account" value="1">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
              Ya, Hapus
            </button>
          </form>
          <button class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition"
            onclick="cancelDelete()">Batal</button>
        </div>
      </div>
    </div>

    <script>
      // Fungsi untuk preview gambar sebelum upload
      function previewImage(event) {
        const [file] = event.target.files || [];
        if (!file) return;
        
        // Preview image
        const url = URL.createObjectURL(file);
        document.getElementById('profileImage').src = url;
        
        // Auto submit form
        event.target.form.submit();
      }

      // Fungsi untuk menampilkan modal ganti password
      function showPasswordModal() {
        document.getElementById("passwordModal").classList.remove("hidden");
      }

      // Fungsi untuk menyembunyikan modal ganti password
      function hidePasswordModal() {
        document.getElementById("passwordModal").classList.add("hidden");
      }

      // Fungsi untuk menampilkan konfirmasi penghapusan akun
      function showDeleteConfirmation() {
        document.getElementById("deleteConfirmation").classList.remove("hidden");
      }

      // Fungsi untuk membatalkan penghapusan akun
      function cancelDelete() {
        document.getElementById("deleteConfirmation").classList.add("hidden");
      }

      // Tutup modal ketika klik di luar konten modal
      document.addEventListener('click', function(event) {
        const passwordModal = document.getElementById('passwordModal');
        const deleteModal = document.getElementById('deleteConfirmation');
        
        if (event.target === passwordModal) {
          hidePasswordModal();
        }
        if (event.target === deleteModal) {
          cancelDelete();
        }
      });
    </script>
</body>
</html>