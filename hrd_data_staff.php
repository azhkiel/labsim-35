<?php
session_start();
include("config.php");

// Ambil data karyawan dari database
$query = "SELECT da.id_detail, da.nama, da.jabatan, da.status, da.departemen 
          FROM detail_akun da 
          JOIN akun_login al ON da.id_login = al.id_login 
          WHERE al.role = 'karyawan' 
          ORDER BY da.id_detail";
$result = mysqli_query($conn, $query);

$karyawan = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $karyawan[] = $row;
    }
}

// Ambil pesan notifikasi dari session
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Fungsi untuk menentukan status badge
function getStatusBadge($jabatan) {
    if (stripos($jabatan, 'kepala') !== false || stripos($jabatan, 'head') !== false) {
        return ['class' => 'bg-rose-300/90', 'text' => 'Kepala Dept.'];
    } elseif (stripos($jabatan, 'staff') !== false || in_array($jabatan, ['Waitress', 'Barista'])) {
        return ['class' => 'bg-yellow-300/80', 'text' => 'Staff'];
    }
    return ['class' => 'bg-green-300/80', 'text' => $jabatan];
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan | Gruduk Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .side-photo {
            background-image: url('Assets/bg-sidebar.jpg');
            background-size: cover;
            background-position: center;
        }
        .side-overlay {
            background: rgba(59, 45, 34, .55);
            backdrop-filter: blur(1px);
        }
        .watermark {
            background-image: url('Assets/Logo Gruduk Cafe.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 62% auto;
            opacity: .18;
            pointer-events: none;
        }
        .icon-pill {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            width: 100%;
            padding: .5rem .85rem;
            height: 3rem;
            border-radius: .6rem;
            font-weight: 600;
            font-size: 14px;
            color: #1f2937;
            background: rgba(255, 255, 255, .92);
            transition: .15s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .12);
        }
        .icon-pill:hover {
            background: #fff;
        }
        .icon-pill.active {
            background: #c8b699;
        }
    </style>
</head>
<body class="min-h-screen bg-[#efe5cf]">

<div class="mx-auto max-w-[1500px] grid grid-cols-1 md:grid-cols-[360px,1fr] gap-6 px-3 md:px-8 py-6 items-start">
    
    <!-- Sidebar -->
    <aside class="md:sticky md:top-6">
        <div class="side-photo rounded-2xl overflow-hidden shadow-lg">
            <div class="side-overlay p-5 min-h-[720px] flex flex-col gap-4 text-white">
                
                <!-- Brand -->
                <div class="flex items-center gap-3 mb-2">
                    <img src="Assets/logo gruduk new.png" alt="Gruduk Cafe" class="w-12 h-12 object-contain drop-shadow">
                    <div class="text-2xl font-extrabold tracking-wide leading-tight">
                        Gruduk <span class="text-[#efe5cf]">Cafe</span>
                    </div>
                </div>

                <!-- Menu Navigation -->
                <nav class="flex flex-col gap-2">
                    <a href="hrd_dashboard.php" class="icon-pill">
                        <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 3l9 8-1.5 1.5L12 6l-7.5 6.5L3 11l9-8z"/>
                                <path d="M5 13h14v8H5z"/>
                            </svg>
                        </span>
                        <span class="label">Dashboard</span>
                    </a>

                    <a href="hrd_data_staff.php" class="icon-pill active">
                        <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3z"/>
                                <path d="M8 13c-2.33 0-7 1.17-7 3.5V20h14v-3.5C15 14.17 10.33 13 8 13zM16 13c-.29 0-.62.02-.97.05 1.16.84 1.97 1.94 1.97 3.45V20h6v-3.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
                        </span>
                        <span class="label">Data Karyawan</span>
                    </a>

                    <a href="hrd_cuti-izin.php" class="icon-pill">
                        <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 18V8.236l7.386 5.676a1 1 0 001.228 0L20 8.236V18H4z"/>
                            </svg>
                        </span>
                        <span class="label">Cuti / Izin</span>
                    </a>

                    <a href="hrd_absensi.php" class="icon-pill">
                        <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .9-2 2v13a2 2 0 002 2h14a2 2 0 002-2V6c0-1.1-.89-2-2-2zm0 15H5V9h14v10z"/>
                            </svg>
                        </span>
                        <span class="label">Absensi</span>
                    </a>

                    <a href="hrd_biodata.php" class="icon-pill">
                        <span class="w-8 h-8 shrink-0 inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                            </svg>
                        </span>
                        <span class="label">Biodata</span>
                    </a>
                </nav>

                <!-- Logout -->
                <div class="mt-auto">
                    <a href="signin_as.php" class="w-full rounded-full bg-[#6b4b3e] hover:bg-[#b38963] text-white font-semibold text-sm py-2 shadow block text-center">
                        Log out
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main>
        <div class="relative mx-auto w-full max-w-[1200px] rounded-2xl bg-white shadow-[0_20px_60px_-20px_rgba(0,0,0,.35)] min-h-[720px] overflow-hidden">
            <div class="absolute inset-0 watermark"></div>

            <div class="relative p-6 md:p-8">
                <!-- Title -->
                <div class="mx-auto w-full max-w-[1050px]">
                    <div class="rounded-lg bg-gray-200 text-center">
                        <h1 class="py-4 text-4xl md:text-5xl font-extrabold italic tracking-wide text-[#4b3a2d]">
                            DATA KARYAWAN
                        </h1>
                    </div>
                </div>

                <!-- Employee Table -->
                <div class="mt-6 mx-auto w-full max-w-[1050px]">
                    <!-- Table Header -->
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-2 text-center bg-gray-200 py-2 rounded-md text-base md:text-lg font-semibold text-gray-700">ID</div>
                        <div class="col-span-5 text-center bg-gray-200 py-2 rounded-md text-base md:text-lg font-semibold text-gray-700">Nama Lengkap</div>
                        <div class="col-span-3 text-center bg-gray-200 py-2 rounded-md text-base md:text-lg font-semibold text-gray-700">STATUS</div>
                        <div class="col-span-2 text-center bg-gray-200 py-2 rounded-md text-base md:text-lg font-semibold text-gray-700">AKSI</div>
                    </div>

                    <!-- Table Rows -->
                    <?php if (!empty($karyawan)): ?>
                        <?php foreach ($karyawan as $k): ?>
                            <?php $badge = getStatusBadge($k['jabatan']); ?>
                            <div class="mt-4 grid grid-cols-12 gap-4 items-center">
                                <div class="col-span-2 text-center bg-gray-100 py-2 rounded-md text-lg font-semibold text-gray-800">
                                    <?= htmlspecialchars($k['id_detail']) ?>
                                </div>
                                <div class="col-span-5">
                                    <div class="bg-white/90 border border-gray-200 rounded-md px-4 py-2 text-lg">
                                        <?= htmlspecialchars($k['nama']) ?>
                                    </div>
                                </div>
                                <div class="col-span-3 flex justify-center">
                                    <span class="px-6 py-2 rounded-full <?= $badge['class'] ?> text-gray-900 font-semibold">
                                        <?= htmlspecialchars($badge['text']) ?>
                                    </span>
                                </div>
                                <div class="col-span-2 flex justify-center">
                                    <button onclick="showDetailModal(<?= $k['id_detail'] ?>)" 
                                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm font-semibold">
                                        Detail
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="mt-4 text-center py-8 text-gray-500">
                            Tidak ada data karyawan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Detail Karyawan -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Detail Data Karyawan</h3>
            <button type="button" onclick="hideDetailModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div id="detailContent" class="space-y-4">
            <!-- Content will be loaded via JavaScript -->
        </div>
        
        <div class="flex justify-end mt-6">
            <button type="button" onclick="hideDetailModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Notifications -->
<?php if ($success_message): ?>
<div id="successAlert" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    <?= htmlspecialchars($success_message) ?>
</div>
<?php endif; ?>

<?php if ($error_message): ?>
<div id="errorAlert" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <?= htmlspecialchars($error_message) ?>
</div>
<?php endif; ?>

<!-- JavaScript -->
<script>
function showDetailModal(idDetail) {
    fetch(`get_karyawan_detail.php?id=${idDetail}`)
        .then(response => response.json())
        .then(data => {
            const content = data.success ? `
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">ID Karyawan</label>
                        <p class="text-gray-900">${data.data.id_detail}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Nama</label>
                        <p class="text-gray-900">${data.data.nama}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Jabatan</label>
                        <p class="text-gray-900">${data.data.jabatan}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Departemen</label>
                        <p class="text-gray-900">${data.data.departemen}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Status</label>
                        <p class="text-gray-900">${data.data.status}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Email</label>
                        <p class="text-gray-900">${data.data.email || '-'}</p>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700">Alamat</label>
                        <p class="text-gray-900">${data.data.alamat || '-'}</p>
                    </div>
                </div>
            ` : '<div class="text-center text-red-600">Gagal memuat data karyawan.</div>';
            
            document.getElementById('detailContent').innerHTML = content;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('detailContent').innerHTML = '<div class="text-center text-red-600">Terjadi error saat memuat data.</div>';
        });
    
    document.getElementById('detailModal').classList.remove('hidden');
}

function hideDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Close modal on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('detailModal');
    if (e.target === modal) hideDetailModal();
});

// Auto-hide notifications
setTimeout(() => {
    ['successAlert', 'errorAlert'].forEach(id => {
        const alert = document.getElementById(id);
        if (alert) alert.remove();
    });
}, 5000);
</script>

<?php include('modal_add_staff.php'); ?>

</body>
</html>