<?php
include 'Koneksi.php';

$id = $_GET['id'];
$query = 'SELECT * FROM public."Connect with Me" WHERE "ID" = $1';
$result = pg_query_params($db_conn, $query, array($id));
$data = pg_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesan - Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h2 class="h4 mb-0"><i class="fas fa-edit me-2"></i>Edit Data Pesan</h2>
            </div>
            <div class="card-body">
                <form action="update.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $data['ID']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['Nama']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data['Email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pesan</label>
                        <textarea name="pesan" class="form-control" rows="5" required><?php echo htmlspecialchars($data['Pesan']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                    <a href="tampilan.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>