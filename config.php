<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'grudukcafe';

$conn = new mysqli($hostname, $username, $password, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
$error_message='';
$success_message='sukses mengubah';
?>