<?php
session_start(); // Tambahkan ini di paling atas
include 'Koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$query = 'SELECT "ID", "Nama", "Email", "Pesan" FROM public."Connect with Me" ORDER BY "ID" DESC'; 
$result = pg_query($db_conn, $query);

if (!$result) {
    die("
    <div class='container mt-4'>
        <div class='alert alert-danger'>
            <h3>GAGAL MENAMPILKAN DATA</h3>
            <p>Terjadi error saat menjalankan query SELECT.</p>
            <p>Detail Error: " . pg_last_error($db_conn) . "</p>
        </div>
    </div>
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kontak - Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0"><i class="fas fa-database me-2"></i>Pesan Masuk (Connect with Me)</h1>
                    <div>
                        <span class="badge bg-light text-primary fs-6">
                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['username']); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="../index.html" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Portofolio
                    </a>
                    <a href="logout.php" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </a>
                </div>

                <?php
                $jumlah_data = pg_num_rows($result);
                if ($jumlah_data > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Pesan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = pg_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['ID']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Nama']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                        <td><?php echo nl2br(htmlspecialchars($row['Pesan'])); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="edit.php?id=<?php echo $row['ID']; ?>" class="btn btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="hapus.php?id=<?php echo $row['ID']; ?>" class="btn btn-danger" 
                                                    onclick="return confirm('Yakin hapus data ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum ada pesan masuk</h4>
                        <p class="text-muted">Silakan kirim pesan melalui formulir kontak di halaman utama.</p>
                        <div class="mt-4">
                            <a href="../index.html" class="btn btn-outline-primary me-2">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Portofolio
                            </a>
                            <a href="logout.php" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php pg_close($db_conn); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>