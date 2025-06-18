<?php
require_once 'config/config.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
  // Log error ke file, tapi jangan tampilkan ke user
  error_log("Database Connection Failed: " . mysqli_connect_error());
  // Tampilkan pesan umum yang tidak membocorkan detail server
  die("Koneksi ke database gagal. Silakan coba lagi nanti.");
}
?>