<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: tampilan.php");
    exit;
}

include 'Koneksi.php';
if (!$db_conn) {
    die("Koneksi database gagal: " . pg_last_error($db_conn));
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username/email dan password harus diisi.';
    } else {
        $query = "SELECT * FROM users WHERE username = $1 OR email = $1 LIMIT 1";
        $result = pg_query_params($db_conn, $query, array($username)); 

        if ($result === false) {
            $error = 'Terjadi kesalahan database: ' . pg_last_error($db_conn);
        } elseif (pg_num_rows($result) > 0) {
            $user = pg_fetch_assoc($result);

            if (isset($user['password']) && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                header("Location: tampilan.php");
                exit;
            } else {
                $error = 'Password salah!';
            }
        } else {
            $error = 'Username atau email tidak ditemukan!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UTS Pemrograman Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  </head>
  <body class="bg-light">
    <div class="container">
      <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-4">
          <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">
              <div class="text-center mb-4">
                <i class="fas fa-lock fa-3x text-primary mb-3"></i>
                <h2 class="card-title">Login Sistem</h2>
                <p class="text-muted">Masuk ke dashboard Anda</p>
              </div>

              <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="fas fa-exclamation-triangle me-2"></i>
                  <?= htmlspecialchars($error); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php endif; ?>

              <form method="POST" novalidate>
                <div class="mb-3">
                  <label for="username" class="form-label">
                    <i class="fas fa-user me-2"></i>Username atau Email
                  </label>
                  <input type="text" class="form-control" id="username" name="username" required
                        value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">
                    <i class="fas fa-key me-2"></i>Password
                  </label>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">
                  <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
              </form>

              <div class="text-center mt-4">
                <p class="mb-0">Belum punya akun? 
                  <a href="register.php" class="text-decoration-none">Daftar di sini</a>
                </p>
              </div>

              <div class="text-center mt-3">
                <a href="../index.html" class="btn btn-outline-secondary btn-sm">
                  <i class="fas fa-arrow-left me-1"></i>Kembali ke Portfolio
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>