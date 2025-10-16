<?php
session_start();

// Simpan pesan logout sukses sebelum menghancurkan session
$_SESSION['logout_message'] = "Anda telah berhasil logout.";

session_unset();
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit;
?>