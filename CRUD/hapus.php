<?php
include 'Koneksi.php';

$id = $_GET['id'];

$query = 'DELETE FROM public."Connect with Me" WHERE "ID" = $1';
$result = pg_query_params($db_conn, $query, array($id));

if ($result) {
    header("Location: tampilan.php?status=deleted");
} else {
    echo "<div class='alert alert-danger'>Gagal menghapus data</div>";
}

pg_close($db_conn);
?>