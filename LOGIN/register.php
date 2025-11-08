<?php
// UTS/register.php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

include 'config.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Semua field harus diisi!';
    } elseif ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak sesuai!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        $check_query = "SELECT * FROM users WHERE username = $1 OR email = $2";
        $check_result = pg_query_params($db_conn, $check_query, array($username, $email));

        if (pg_num_rows($check_result) > 0) {
            $error = 'Username atau email sudah terdaftar!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO users (username, email, password) VALUES ($1, $2, $3)";
            $result = pg_query_params($db_conn, $query, array($username, $email, $hashed_password));

            if ($result) {
                $success = 'Registrasi berhasil! Silakan login.';
                $_POST = array();
            } else {
                $error = 'Terjadi kesalahan saat registrasi!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UTS Pemrograman Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-plus fa-3x text-success mb-3"></i>
                            <h2 class="card-title">Registrasi Akun</h2>
                            <p class="text-muted">Buat akun baru untuk mengakses sistem</p>
                        </div>

                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-user me-2"></i>Username</label>
                                <input type="text" class="form-control" name="username" 
                                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                                <input type="email" class="form-control" name="email" 
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-key me-2"></i>Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-key me-2"></i>Konfirmasi Password</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2">
                                <i class="fas fa-user-plus me-2"></i>Daftar
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="mb-0">Sudah punya akun? 
                                <a href="index.php" class="text-decoration-none">Login di sini</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>