<?php
/* Kredensial database. Dengan asumsi Anda menjalankan MySQL
server dengan pengaturan default (user 'root' tanpa password)
*/
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'web_ptei');
/* Coba sambungkan ke database MySQL */
$link = mysqli_connect(
    DB_SERVER,
    DB_USERNAME,
    DB_PASSWORD,
    DB_NAME
);
// Cek Koneksi
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
