<?php
// config.php

// Nonaktifkan error display di production
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Konfigurasi koneksi database
define('DB_HOST', 'localhost');

// Hosting
// define('DB_USER', 'android');
// define('DB_PASS', '");
// define('DB_NAME', '');

// Lokal
define('DB_USER', 'root');
define('DB_PASS', 'Maulana1');
define('DB_NAME', 'batiknusantara');

// Optional: Log error ke file khusus
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
?>