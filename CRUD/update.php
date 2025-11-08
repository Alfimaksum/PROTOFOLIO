<?php
include 'Koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$pesan = $_POST['pesan'];

$query = 'UPDATE public."Connect with Me" SET "Nama"=$1, "Email"=$2, "Pesan"=$3 WHERE "ID"=$4';
$params = array($nama, $email, $pesan, $id);
$result = pg_query_params($db_conn, $query, $params);

if ($result) {
    header("Location: tampilan.php?status=updated");
} else {
    echo "<div class='alert alert-danger'>Gagal mengubah data.</div>";
}

pg_close($db_conn);
?>