<?php
session_start();
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data for akun_login
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Get form data for detail_akun
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $agama = mysqli_real_escape_string($conn, $_POST['agama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $departemen = mysqli_real_escape_string($conn, $_POST['departemen']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Set default role untuk karyawan
    $role = 'karyawan';

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Check if username already exists
        $check_query = "SELECT id_login FROM akun_login WHERE username = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            throw new Exception("Username sudah digunakan. Silakan pilih username lain.");
        }
        mysqli_stmt_close($check_stmt);

        // Insert into akun_login table
        $query1 = "INSERT INTO akun_login (username, password, role) VALUES (?, ?, ?)";
        $stmt1 = mysqli_prepare($conn, $query1);
        mysqli_stmt_bind_param($stmt1, "sss", $username, $password, $role);
        
        if (!mysqli_stmt_execute($stmt1)) {
            throw new Exception("Gagal menyimpan data login: " . mysqli_error($conn));
        }
        
        $id_login = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt1);

        // Insert into detail_akun table dengan struktur yang sesuai
        $query2 = "INSERT INTO detail_akun (
                    id_login, nama, alamat, tempat_lahir, tanggal_lahir, 
                    jenis_kelamin, agama, email, departemen, jabatan, status, role
                   ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt2 = mysqli_prepare($conn, $query2);
        
        // Bind parameters sesuai dengan urutan di query
        mysqli_stmt_bind_param(
            $stmt2, 
            "isssssssssss", 
            $id_login, $nama, $alamat, $tempat_lahir, $tanggal_lahir,
            $jenis_kelamin, $agama, $email, $departemen, $jabatan, $status, $role
        );
        
        if (!mysqli_stmt_execute($stmt2)) {
            throw new Exception("Gagal menyimpan detail akun: " . mysqli_error($conn));
        }
        
        mysqli_stmt_close($stmt2);

        // Commit transaction
        mysqli_commit($conn);
        
        $_SESSION['success_message'] = "Staff berhasil ditambahkan!";
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        $_SESSION['error_message'] = $e->getMessage();
    }

    // Redirect back to staff data page
    header("Location: hrd_data_staff.php");
    exit();
} else {
    header("Location: hrd_data_staff.php");
    exit();
}
?>