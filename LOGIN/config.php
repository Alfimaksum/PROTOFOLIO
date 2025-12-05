<?php
$host = 'localhost';
$port = '5432';
$dbname = 'PROTOFOLIO';   
$user = 'postgres';
$password = 'Maksumm_06'; 

$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
$dbconn = pg_connect($conn_string);

if (!$dbconn) {
    die("Koneksi ke database gagal.");
}
?>
