<?php
session_start();
// Perbaikan: Sesuaikan dengan session di login.php
if (isset($_SESSION['username'])) {
    header("Location: tampilan.php");
    exit;
}

include 'Koneksi.php';
// PERBAIKAN: Gunakan variabel yang sama dengan Koneksi.php
if (!$db_conn) { // Ganti $dbconn menjadi $db_conn
    die('Koneksi database gagal: ' . pg_last_error($db_conn)); // Tambahkan parameter
}

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Semua field harus diisi!';
    } elseif (strlen($username) > 50) {
        $error = 'Username maksimal 50 karakter!';
    } elseif (strlen($email) > 100) {
        $error = 'Email maksimal 100 karakter!';
    } elseif ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak sesuai!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } else {
        // PERBAIKAN: Gunakan $db_conn yang konsisten
        $check_query = "SELECT * FROM users WHERE username = $1 OR email = $2";
        $check_result = pg_query_params($db_conn, $check_query, array($username, $email)); // Ganti $dbconn menjadi $db_conn

        if (pg_num_rows($check_result) > 0) {
            $error = 'Username atau email sudah terdaftar!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO users (username, email, password) VALUES ($1, $2, $3)";
            // PERBAIKAN: Gunakan $db_conn yang konsisten
            $result = pg_query_params($db_conn, $query, array($username, $email, $hashed_password)); // Ganti $dbconn menjadi $db_conn
            if ($result) {
                $success = 'Registrasi berhasil! Silakan login.';
                $_POST = array();
            } else {
                $error = 'Terjadi kesalahan saat registrasi: ' . pg_last_error($db_conn);
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
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        .register-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.2);
            background: var(--light);
        }
        
        .btn-primary {
            background: var(--primary);
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
        }
        
        .icon-circle {
            background: var(--primary);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .text-primary {
            color: var(--primary) !important;
        }
        
        .form-label {
            color: var(--dark);
            font-weight: 600;
        }
        
        .char-count {
            font-size: 0.8rem;
            color: var(--secondary);
        }
    </style>
</head>
<body class="gradient-bg">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card register-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="icon-circle">
                                <i class="fas fa-user-plus fa-2x text-white"></i>
                            </div>
                            <h2 class="card-title text-dark fw-bold">Registrasi Akun</h2>
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

                        <form method="POST" id="registerForm">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user me-2 text-primary"></i>Username
                                </label>
                                <input type="text" class="form-control form-control-lg" name="username" 
                                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                                    placeholder="Masukkan username (maks. 50 karakter)" 
                                    maxlength="50"
                                    required>
                                <div class="char-count">
                                    <span id="usernameCount">0</span>/50 karakter
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Email
                                </label>
                                <input type="email" class="form-control form-control-lg" name="email" 
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                    placeholder="Masukkan email (maks. 100 karakter)"
                                    maxlength="100"
                                    required>
                                <div class="char-count">
                                    <span id="emailCount">0</span>/100 karakter
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-key me-2 text-primary"></i>Password
                                </label>
                                <input type="password" class="form-control form-control-lg" name="password" 
                                    placeholder="Minimal 6 karakter" 
                                    minlength="6"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-key me-2 text-primary"></i>Konfirmasi Password
                                </label>
                                <input type="password" class="form-control form-control-lg" name="confirm_password" 
                                    placeholder="Ulangi password" 
                                    minlength="6"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                            </button>
                        </form>
                        <div class="text-center mt-4">
                            <p class="mb-0 text-muted">Sudah punya akun? 
                                <a href="login.php" class="text-decoration-none fw-bold text-primary">Login di sini</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.querySelector('input[name="username"]');
            const emailInput = document.querySelector('input[name="email"]');
            const usernameCount = document.getElementById('usernameCount');
            const emailCount = document.getElementById('emailCount');
            
            usernameInput.addEventListener('input', function() {
                usernameCount.textContent = this.value.length;
                usernameCount.style.color = this.value.length > 45 ? 'red' : 'var(--secondary)';
            });
            
            emailInput.addEventListener('input', function() {
                emailCount.textContent = this.value.length;
                emailCount.style.color = this.value.length > 90 ? 'red' : 'var(--secondary)';
            });
            
            usernameCount.textContent = usernameInput.value.length;
            emailCount.textContent = emailInput.value.length;
        });
    </script>
</body>
</html>