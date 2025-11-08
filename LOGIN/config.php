<?php
$host = 'localhost';
$port = '5432';
$dbname = 'PROTOFOLIO';
$user = 'postgres';
$password = 'Maksumm_06';

$db_conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$db_conn) {
    die("Koneksi database gagal: " . pg_last_error());
}

// Buat tabel users jika belum ada
$create_table_query = "
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

pg_query($db_conn, $create_table_query);

// Buat tabel data_user jika belum ada
$create_data_table = "
CREATE TABLE IF NOT EXISTS data_user (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
pg_query($db_conn, $create_data_table);
?>