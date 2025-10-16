<?php
session_start();
include("config.php");

if (isset($_GET['id'])) {
    $id_detail = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query = "SELECT da.*, al.username 
              FROM detail_akun da 
              JOIN akun_login al ON da.id_login = al.id_login 
              WHERE da.id_detail = '$id_detail'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'ID tidak valid'
    ]);
}
?>