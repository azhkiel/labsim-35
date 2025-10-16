<?php
session_start();
include("config.php");

// Ambil data HRD yang sedang login
if (isset($_SESSION['id_login'])) {
    $id_login = $_SESSION['id_login'];
    $query = "SELECT da.*, al.username 
              FROM detail_akun da 
              JOIN akun_login al ON da.id_login = al.id_login 
              WHERE da.id_login = '$id_login' AND al.role = 'hrd' 
              LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
}

// Handle upload foto profil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['foto_profile'])) {
    if ($_FILES['foto_profile']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/fotoProfile/";
        
        // Buat folder jika belum ada
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES["foto_profile"]["name"], PATHINFO_EXTENSION);
        $filename = "profile_" . $row['id_detail'] . "_" . date("Y-m-d_H-i-s") . "." . $file_extension;
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
                        $update_query = "UPDATE detail_akun SET foto_profile = '$filename' WHERE id_detail = '" . $row['id_detail'] . "'";
                        if (mysqli_query($conn, $update_query)) {
                            $success_message = "Foto profil berhasil diupdate!";
                            // Refresh data
                            $row['foto_profile'] = $filename;
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
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Biodata HRD | Gruduk Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* ===== Sidebar background photo ===== */
        .side-photo {
            background-image: url('Assets/bg-sidebar.jpg');
            background-size: cover;
            background-position: center;
        }

        .side-overlay {
            background: rgba(59, 45, 34, .55);
            backdrop-filter: blur(1px);
        }

        /* ===== Watermark kanvas utama ===== */
        .watermark {
            background-image: url('Assets/Logo Gruduk Cafe.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 58% auto;
            opacity: .16;
            pointer-events: none;
        }

        /* ===== Tombol sidebar (ikon + label) ===== */
        .icon-pill {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            width: 100%;
            padding: .5rem .9rem;
            height: 3rem;
            border-radius: .6rem;
            font-weight: 600;
            font-size: 14px;
            color: #1f2937;
            background: #ffffff;
            transition: .15s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .12);
        }

        .icon-pill:hover {
            background: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
        }

        .icon-pill svg {
            display: block;
        }

        .icon-pill .label {
            line-height: 1;
        }
    </style>
</head>

<body class="min-h-screen bg-[#efe5cf]">

    <!-- ===== LAYOUT (tanpa navbar) ===== -->
    <div class="max-w-[120rem] mx-auto grid grid-cols-12 gap-4 md:gap-6 px-3 md:px-8 py-4 items-start">

        <!-- ===== SIDEBAR ===== -->
        <aside class="col-span-12 md:col-span-4 lg:col-span-3">
            <div class="side-photo rounded-xl overflow-hidden shadow-lg">
                <div class="side-overlay p-4 md:p-6 min-h-screen flex flex-col gap-3 text-white">

                    <!-- Brand -->
                    <div class="flex items-center gap-3 mb-5">
                        <img src="Assets/logo gruduk new.png" alt="Logo Gruduk Cafe"
                            class="w-10 h-10 md:w-12 md:h-12 object-contain drop-shadow">
                        <div class="text-2xl font-extrabold tracking-wide leading-tight">
                            Gruduk <span class="text-[#efe5cf]">Cafe</span>
                        </div>
                    </div>

                    <!-- Menu -->
                    <a href="hrd_dashboard.php" class="icon-pill">
                        <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
                            <!-- home -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path d="M12 3l9 8-1.5 1.5L12 6l-7.5 6.5L3 11l9-8z" />
                                <path d="M5 13h14v8H5z" />
                            </svg>
                        </span>
                        <span class="label">Dashboard</span>
                    </a>

                    <a href="hrd_data_staff.php" class="icon-pill">
                        <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
                            <!-- users -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3z" />
                                <path
                                    d="M8 13c-2.33 0-7 1.17-7 3.5V20h14v-3.5C15 14.17 10.33 13 8 13zM16 13c-.29 0-.62.02-.97.05 1.16.84 1.97 1.94 1.97 3.45V20h6v-3.5c0-2.33-4.67-3.5-7-3.5z" />
                            </svg>
                        </span>
                        <span class="label">Data Karyawan</span>
                    </a>

                    <a href="hrd_cuti-izin.php" class="icon-pill">
                        <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
                            <!-- mail -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 18V8.236l7.386 5.676a1 1 0 001.228 0L20 8.236V18H4z" />
                            </svg>
                        </span>
                        <span class="label">Cuti / Izin</span>
                    </a>

                    <a href="hrd_absensi.php" class="icon-pill">
                        <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
                            <!-- calendar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .9-2 2v13a2 2 0 002 2h14a2 2 0 002-2V6c0-1.1-.89-2-2-2zm0 15H5V9h14v10z" />
                            </svg>
                        </span>
                        <span class="label">Absensi</span>
                    </a>

                    <a href="hrd_biodata.php" class="icon-pill" style="background:#c8b699;">
                        <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 18V8.236l7.386 5.676a1 1 0 001.228 0L20 8.236V18H4z" />
                            </svg>
                        </span>
                        <span class="label">Biodata</span>
                    </a>

                    <!-- Log out -->
                    <div class="mt-auto">
                        <a href="signin_as.php"
                            class="w-full rounded-full bg-[#6b4b3e] hover:bg-[#b38963] text-white font-semibold text-sm py-2 shadow block text-center">
                            Log out
                        </a>
                    </div>

                </div>
            </div>
        </aside>

        <!-- ===== KANVAS BIODATA ===== -->
        <main class="col-span-12 md:col-span-8 lg:col-span-9">
            <div
                class="relative rounded-xl bg-white shadow-[0_20px_60px_-20px_rgba(0,0,0,.35)] min-h-screen overflow-hidden flex flex-col">
                <!-- watermark -->
                <div class="absolute inset-0 watermark"></div>

                <div class="relative p-6 md:p-8 flex-1">
                    <!-- Judul -->
                    <div class="mx-auto max-w-4xl">
                        <div class="rounded-lg bg-[#b6a187] text-center">
                            <h1 class="py-4 text-3xl md:text-4xl font-extrabold italic tracking-wide text-[#4b3a2d]">
                                BIODATA
                            </h1>
                        </div>
                    </div>

                    <!-- Konten -->
                    <form method="POST" action="" enctype="multipart/form-data" class="mx-auto mt-6 max-w-4xl grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Foto Profil -->
                        <div class="md:col-span-1">
                            <div
                                class="rounded-xl overflow-hidden ring-1 ring-black/10 bg-gray-100 w-[190px] h-[220px] mx-auto relative">
                                <img id="profileImage" 
                                    src="<?php 
                                        if (!empty($row['foto_profile'])) {
                                            echo 'uploads/fotoProfile/' . htmlspecialchars($row['foto_profile']);
                                        } else {
                                            echo 'Assets/Fahras PDH.jpg';
                                        }
                                    ?>" 
                                    alt="Foto Profil" 
                                    class="w-full h-full object-cover">
                                <input id="fileInput" type="file" name="foto_profile" accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        aria-label="Ganti foto profil" onchange="previewImage(event)">
                            </div>
                            <label for="fileInput"
                                    class="mt-3 block w-[190px] mx-auto text-center px-4 py-2 rounded-md bg-[#6b4b3e] hover:bg-[#b38963] text-white text-sm font-semibold cursor-pointer">
                                Ganti Foto
                            </label>
                            <!-- Tombol Ganti Password -->
                            <div class="mt-6 text-center">
                                <button type="button" onclick="showPasswordModal()"
                                    class="w-[190px] mx-auto px-4 py-2 rounded-md bg-[#6b4b3e] hover:bg-[#b38963] text-white text-sm font-semibold transition">
                                    Ganti Password
                                </button>
                            </div>
                        </div>

                        <!-- Detail Biodata -->
                        <div class="md:col-span-2">
                            <div class="bg-white/80 backdrop-blur-sm rounded-xl ring-1 ring-black/10 p-4 md:p-5">
                                <dl class="divide-y divide-gray-100">
                                    <div class="grid grid-cols-12 gap-2 py-2">
                                        <dt class="col-span-5 md:col-span-4 text-sm text-gray-600">Nama</dt>
                                        <dd class="col-span-7 md:col-span-8 text-sm font-semibold text-gray-900">
                                            <?php echo isset($row['nama']) ? htmlspecialchars($row['nama']) : 'Fahras Zeilicha'; ?>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-12 gap-2 py-2">
                                        <dt class="col-span-5 md:col-span-4 text-sm text-gray-600">Alamat</dt>
                                        <dd class="col-span-7 md:col-span-8 text-sm font-semibold text-gray-900">
                                            <?php echo isset($row['alamat']) ? htmlspecialchars($row['alamat']) : 'Perumahan Citraland No 5, Surabaya'; ?>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-12 gap-2 py-2">
                                        <dt class="col-span-5 md:col-span-4 text-sm text-gray-600">TTL</dt>
                                        <dd class="col-span-7 md:col-span-8 text-sm font-semibold text-gray-900">
                                            <?php 
                                            $tempat_lahir = isset($row['tempat_lahir']) ? htmlspecialchars($row['tempat_lahir']) : 'Surabaya';
                                            $tanggal_lahir = isset($row['tanggal_lahir']) ? date('d F Y', strtotime($row['tanggal_lahir'])) : '30 Desember 2010';
                                            echo $tempat_lahir . ', ' . $tanggal_lahir;
                                            ?>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-12 gap-2 py-2">
                                        <dt class="col-span-5 md:col-span-4 text-sm text-gray-600">Jenis Kelamin</dt>
                                        <dd class="col-span-7 md:col-span-8 text-sm font-semibold text-gray-900">
                                            <?php echo isset($row['jenis_kelamin']) ? htmlspecialchars($row['jenis_kelamin']) : 'Perempuan'; ?>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-12 gap-2 py-2">
                                        <dt class="col-span-5 md:col-span-4 text-sm text-gray-600">Agama</dt>
                                        <dd class="col-span-7 md:col-span-8 text-sm font-semibold text-gray-900">
                                            <?php echo isset($row['agama']) ? htmlspecialchars($row['agama']) : 'Islam'; ?>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-12 gap-2 py-2">
                                        <dt class="col-span-5 md:col-span-4 text-sm text-gray-600">Email</dt>
                                        <dd class="col-span-7 md:col-span-8 text-sm font-semibold text-gray-900">
                                            <a href="mailto:<?php echo isset($row['email']) ? htmlspecialchars($row['email']) : 'fahrasdt16@gmail.com'; ?>"
                                                class="hover:underline">
                                                <?php echo isset($row['email']) ? htmlspecialchars($row['email']) : 'fahrasdt16@gmail.com'; ?>
                                            </a>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-12 gap-2 py-2">
                                        <dt class="col-span-5 md:col-span-4 text-sm text-gray-600">Jabatan</dt>
                                        <dd class="col-span-7 md:col-span-8 text-sm font-semibold text-gray-900">
                                            <?php echo isset($row['jabatan']) ? htmlspecialchars($row['jabatan']) : 'HRD'; ?>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-12 gap-2 py-2">
                                        <dt class="col-span-5 md:col-span-4 text-sm text-gray-600">Departemen</dt>
                                        <dd class="col-span-7 md:col-span-8 text-sm font-semibold text-gray-900">
                                            <?php echo isset($row['departemen']) ? htmlspecialchars($row['departemen']) : 'Human Resources'; ?>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </form> <!-- /Konten -->
                </div> <!-- /p-6 -->
            </div> <!-- /card putih -->
        </main>
    </div> <!-- /grid -->

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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="new_password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e] focus:border-transparent">
                    </div>
                </div>

                <div class="flex gap-3 justify-end mt-6">
                    <button type="button" onclick="hidePasswordModal()"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" name="change_password"
                        class="px-4 py-2 bg-[#6b4b3e] text-white rounded-md hover:bg-[#b38963] transition">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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

        // Tutup modal ketika klik di luar konten modal
        document.addEventListener('click', function(event) {
            const passwordModal = document.getElementById('passwordModal');
            if (event.target === passwordModal) {
                hidePasswordModal();
            }
        });
    </script>
</body>
</html>