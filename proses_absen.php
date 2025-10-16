<?php
include("config.php");
session_start();

$id_kehadiran = $_SESSION['id_kehadiran'] ?? '8992'; // ID karyawan (ganti sesuai login)
$nama_kehadiran = $_SESSION['nama_kehadiran'] ?? 'Maulana Afrizki';
$waktu_kehadiran = date('Y-m-d H:i:s');

// Upload foto
if (isset($_FILES['foto_absen']) && $_FILES['foto_absen']['error'] == 0) {
    $target_dir = "uploads/absen/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $file_name = time() . "_" . preg_replace("/[^A-Za-z0-9._-]/", "_", $_FILES['foto_absen']['name']);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES['foto_absen']['tmp_name'], $target_file)) {
        // ✅ Simpan ke tabel sesuai struktur yang kamu jelaskan
        $query = "INSERT INTO tbl_kehadiran (id_kehadiran, nama_kehadiran, waktu_kehadiran, foto_absen)
                  VALUES ('$id_kehadiran', '$nama_kehadiran', '$waktu_kehadiran', '$file_name')";

        if (mysqli_query($conn, $query)) {
            header("Location: riwayat kehadiran.php?status=sukses");
            exit;
        } else {
            echo "❌ Gagal menyimpan ke database: " . mysqli_error($conn);
        }
    } else {
        echo "❌ Upload foto gagal.";
    }
} else {
    echo "⚠️ Tidak ada file diunggah.";
}
?>