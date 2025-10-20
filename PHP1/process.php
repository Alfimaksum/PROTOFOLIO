<?php
include 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : ''; 

    if (empty($name) || empty($email) || empty($message)) {
        header("Location: ../index.html?status=error&message=Semua field harus diisi.");
        exit;
    }

    $query = 'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES ($1, $2, $3)';
    
    $params = array($name, $email, $message); 

    $result = pg_query_params($db_conn, $query, $params);

    if ($result) {
        header("Location: ../index.html?status=success_contact");
        exit;
    } else {
        $error_message = urlencode("Gagal menyimpan data. Cek penamaan tabel/kolom di database.");
        header("Location: ../index.html?status=error&message={$error_message}");
        exit;
    }
}


pg_close($db_conn);

header("Location: ../index.html");
exit;
?>