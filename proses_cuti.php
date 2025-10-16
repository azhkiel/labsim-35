<?php
session_start();
include("config.php");

// Cek apakah user sudah login
if (!isset($_SESSION['id_login'])) {
    header("Location: login.php");
    exit;
}

// Ambil data karyawan yang sedang login
$id_login = $_SESSION['id_login'];
$query_karyawan = "SELECT da.* FROM detail_akun da WHERE da.id_login = '$id_login' LIMIT 1";
$result_karyawan = mysqli_query($conn, $query_karyawan);
$karyawan = mysqli_fetch_assoc($result_karyawan);

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_detail = $karyawan['id_detail'];
    $tanggal_mulai = mysqli_real_escape_string($conn, $_POST['tanggal_mulai']);
    $tanggal_akhir = mysqli_real_escape_string($conn, $_POST['tanggal_akhir']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    // Handle file upload jika ada
    $foto_cuti_izin = '';
    if (isset($_FILES['surat_pendukung']) && $_FILES['surat_pendukung']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/cuti_izin/";
        
        // Buat folder jika belum ada
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES["surat_pendukung"]["name"], PATHINFO_EXTENSION);
        $filename = "cuti_" . $id_detail . "_" . date("Y-m-d_H-i-s") . "." . $file_extension;
        $target_file = $target_dir . $filename;
        
        // Check if file is a actual image or PDF
        $check = getimagesize($_FILES["surat_pendukung"]["tmp_name"]);
        $is_pdf = $_FILES["surat_pendukung"]["type"] == "application/pdf";
        
        if($check !== false || $is_pdf) {
            // Check file size (max 5MB)
            if ($_FILES["surat_pendukung"]["size"] > 5000000) {
                $error_message = "Ukuran file terlalu besar. Maksimal 5MB.";
            } else {
                // Allow certain file formats
                $allowed_extensions = ["jpg", "jpeg", "png", "gif", "pdf"];
                if (in_array(strtolower($file_extension), $allowed_extensions)) {
                    if (move_uploaded_file($_FILES["surat_pendukung"]["tmp_name"], $target_file)) {
                        $foto_cuti_izin = $filename;
                    } else {
                        $error_message = "Maaf, terjadi error saat upload file.";
                    }
                } else {
                    $error_message = "Hanya file JPG, JPEG, PNG, GIF & PDF yang diizinkan.";
                }
            }
        } else {
            $error_message = "File bukan gambar atau PDF.";
        }
    }
    
    if (empty($error_message)) {
        // Insert data cuti/izin ke database
        $insert_query = "INSERT INTO cuti_izin_karyawan 
                        (id_detail, tanggal_pengajuan, tanggal_mulai, tanggal_akhir, tipe, keterangan, foto_cuti_izin, action) 
                        VALUES 
                        ('$id_detail', NOW(), '$tanggal_mulai', '$tanggal_akhir', '$tipe', '$keterangan', '$foto_cuti_izin', 'pending')";
        
        if (mysqli_query($conn, $insert_query)) {
            $success_message = "Pengajuan cuti/izin berhasil dikirim! Menunggu persetujuan HRD.";
        } else {
            $error_message = "Gagal menyimpan pengajuan cuti/izin: " . mysqli_error($conn);
        }
    }
}

// Redirect dengan pesan
if (!empty($success_message)) {
    $_SESSION['success_message'] = $success_message;
} elseif (!empty($error_message)) {
    $_SESSION['error_message'] = $error_message;
}

header("Location: cuti izin karyawan.php");
exit;
?>