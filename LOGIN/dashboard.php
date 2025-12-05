<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Maksum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg p-4 border-0 rounded-4">
        <h2 class="text-primary mb-3">Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p class="text-muted">Anda berhasil login. Klik tombol di bawah ini untuk melihat portfolio Anda.</p>
        <a href="../index.html" class="btn btn-success btn-lg">Lihat Portfolio</a>
        <a href="logout.php" class="btn btn-outline-danger btn-lg ms-2">Logout</a>
    </div>
</div>
</body>
</html>
