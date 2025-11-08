<?php
$host = 'localhost';
$port = '5432';
$dbname = 'PROTOFOLIO'; 
$user = 'postgres';
$password = 'Maksumm_06';

$conn_string = "host='{$host}' port='{$port}' dbname='{$dbname}' user='{$user}' password='{$password}'";

$db_conn = @pg_connect($conn_string); 

if (!$db_conn) {
    die("KONEKSI GAGAL Tidak dapat terhubung ke database");
}
?>