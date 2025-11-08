<?php
include 'Koneksi.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : ''; 

    if (empty($name) || empty($email) || empty($message)) {
        header("Location: ../index.html?status=error&message=Semua field harus diisi.");
        exit;
    }

    if (!$db_conn) {
        $error_message = "Koneksi database gagal" . pg_last_error();
        header("Location: ../index.html?status=error&message=" . urlencode($error_message));
        exit;
    }

    $query = 'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES ($1, $2, $3)';
    
    $params = array($name, $email, $message); 

    $result = pg_query_params($db_conn, $query, $params);

    if ($result) {
        header("Location: ../index.html?status=success_contact");
        exit;
    } else {
        $error_detail = pg_last_error($db_conn);
        $error_message = "Gagal menyimpan data. Error: " . $error_detail;
        header("Location: ../index.html?status=error&message=" . urlencode($error_message));
        exit;
    }
} else {
    header("Location: ../index.html");
    exit;
}

pg_close($db_conn);
?>