<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include 'config.php';

// Handle tambah data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_data'])) {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    
    if (!empty($judul) && !empty($deskripsi)) {
        $query = "INSERT INTO data_user (user_id, judul, deskripsi) VALUES ($1, $2, $3)";
        $result = pg_query_params($db_conn, $query, array($_SESSION['user_id'], $judul, $deskripsi));
        
        if ($result) {
            $success_message = "Data berhasil ditambahkan!";
        } else {
            $error_message = "Gagal menambahkan data!";
        }
    }
}

// Handle edit data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_data'])) {
    $id = $_POST['id'];
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    
    if (!empty($judul) && !empty($deskripsi)) {
        $query = "UPDATE data_user SET judul = $1, deskripsi = $2 WHERE id = $3 AND user_id = $4";
        $result = pg_query_params($db_conn, $query, array($judul, $deskripsi, $id, $_SESSION['user_id']));
        
        if ($result) {
            $success_message = "Data berhasil diupdate!";
        } else {
            $error_message = "Gagal mengupdate data!";
        }
    }
}

// Handle hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM data_user WHERE id = $1 AND user_id = $2";
    $result = pg_query_params($db_conn, $query, array($id, $_SESSION['user_id']));
    
    if ($result) {
        $success_message = "Data berhasil dihapus!";
    } else {
        $error_message = "Gagal menghapus data!";
    }
}

// Ambil data user
$query = "SELECT * FROM data_user WHERE user_id = $1 ORDER BY created_at DESC";
$data_result = pg_query_params($db_conn, $query, array($_SESSION['user_id']));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UTS Pemrograman Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user me-1"></i> <?php echo $_SESSION['username']; ?>
                </span>
                <a class="btn btn-outline-light btn-sm" href="logout.php">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Notifikasi -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Manajemen Data</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                        <i class="fas fa-plus me-2"></i>Tambah Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Data User</h5>
            </div>
            <div class="card-body">
                <?php if (pg_num_rows($data_result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = pg_fetch_assoc($data_result)): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['judul']); ?></td>
                                        <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editDataModal"
                                                    data-id="<?php echo $row['id']; ?>"
                                                    data-judul="<?php echo htmlspecialchars($row['judul']); ?>"
                                                    data-deskripsi="<?php echo htmlspecialchars($row['deskripsi']); ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <a href="?hapus=<?php echo $row['id']; ?>" 
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin hapus data ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data. Silakan tambah data baru.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Tambah Data Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_data" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div class="modal fade" id="editDataModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="edit_judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit_data" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script untuk modal edit
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('editDataModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var judul = button.getAttribute('data-judul');
                var deskripsi = button.getAttribute('data-deskripsi');
                
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_judul').value = judul;
                document.getElementById('edit_deskripsi').value = deskripsi;
            });
        });
    </script>
</body>
</html>