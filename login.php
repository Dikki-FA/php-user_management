<?php
session_start();
include 'config/koneksi.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi input kosong
    if (empty($username) || empty($password)){
        $error = "Semua field wajib diisi!";

    } else {
        // Cari user berdasarkan username dan status
        $query  = mysqli_query($conn,
            "SELECT * FROM users WHERE username='$username'"        
            );
        
            $user = mysqli_fetch_assoc($query);

        // Verifikasi password
        if ($user && password_verify($password, $user['password'])) {

            // Simpan session
            $_SESSION['id']       = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['status']   = $user['status'];

            // Catat log aktivitas
            $aktivitas = "Login";
            mysqli_query($conn,
                "INSERT INTO log_activity (user_id, username, aktivitas)
                 VALUES ('{$user['id']}', '{$user['username']}', '$aktivitas')"
            );

            // Redirect ke dashboard
            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Username, password, atau status salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-wrapper">
  <div class="auth-card">

    <!-- Icon & Judul -->
    <div class="text-center mb-3">
      <i class="bi bi-person-circle auth-icon"></i>
      <h5 class="auth-title">LOGIN</h5>
    </div>

    <!-- Alert Error -->
    <?php if ($error): ?>
      <div class="alert alert-danger py-2" style="font-size:13px">
        <i class="bi bi-exclamation-circle me-1"></i> <?= $error ?>
      </div>
    <?php endif; ?>

    <!-- Form -->
    <form method="POST">

      <!-- Username -->
      <div class="mb-3">
        <label class="form-label" style="font-size:13px">Username</label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="bi bi-person"></i>
          </span>
          <input type="text" name="username" class="form-control"
                 placeholder="Masukkan username"
                 value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label class="form-label" style="font-size:13px">Password</label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="bi bi-lock"></i>
          </span>
          <input type="password" name="password" id="password"
                 class="form-control" placeholder="Masukkan password">
          <button type="button" class="btn btn-outline-secondary"
                  onclick="togglePassword()">
            <i class="bi bi-eye" id="eye-icon"></i>
          </button>
        </div>
      </div>

      <!-- Tombol -->
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <button type="reset" class="btn btn-secondary w-100">Reset</button>
      </div>

    </form>

    <!-- Link ke Register -->
    <div class="text-center mt-3">
      <small>Belum punya akun?
        <a href="register.php" class="text-decoration-none fw-semibold">Daftar disini</a>
      </small>
    </div>

  </div>
</div>

<script>
function togglePassword() {
  const input = document.getElementById('password');
  const icon  = document.getElementById('eye-icon');
  if (input.type === 'password') {
    input.type     = 'text';
    icon.className = 'bi bi-eye-slash';
  } else {
    input.type     = 'password';
    icon.className = 'bi bi-eye';
  }
}
</script>

</body>
</html>