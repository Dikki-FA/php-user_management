<?php
session_start();
include 'config/koneksi.php';

// Proteksi: harus login sebagai Admin
if (!isset($_SESSION['id']) || $_SESSION['status'] !== 'Admin') {
    header("Location: dashboard.php");
    exit;
}

$login_id   = $_SESSION['id'];
$login_user = $_SESSION['username'];

$pesan_sukses = "";
$pesan_error  = "";

// ============================================================
// AKSI: UBAH STATUS USER
// ============================================================
if (isset($_GET['ubah_id']) && isset($_GET['status_baru'])) {
    $ubah_id     = (int) $_GET['ubah_id'];
    $status_baru = $_GET['status_baru'];

    if ($ubah_id === 1) {
        $pesan_error = "Akun admin utama tidak bisa diubah statusnya!";
    } elseif (!in_array($status_baru, ['Admin', 'User'])) {
        $pesan_error = "Status tidak valid!";
    } else {
        $row_ubah = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username, status FROM users WHERE id='$ubah_id'"));
        if ($row_ubah) {
            $ubah_username  = $row_ubah['username'];
            $status_lama    = $row_ubah['status'];
            mysqli_query($conn, "UPDATE users SET status='$status_baru' WHERE id='$ubah_id'");
            mysqli_query($conn,
                "INSERT INTO log_activity (user_id, username, aktivitas)
                 VALUES ('$login_id', '$login_user', 'Ubah Status: $ubah_username ($status_lama → $status_baru)')"
            );
            $pesan_sukses = "Status <strong>$ubah_username</strong> berhasil diubah menjadi <strong>$status_baru</strong>!";
        } else {
            $pesan_error = "User tidak ditemukan!";
        }
    }
}

// Ambil semua user kecuali id=1 (admin utama)
$query_users = mysqli_query($conn, "SELECT * FROM users WHERE id != 6 ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pengaturan</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar">
  <div class="brand">
    <i class="bi bi-grid-fill"></i> SISTEM APLIKASI
  </div>
  <nav class="mt-2">
    <a href="dashboard.php" class="nav-link">
      <i class="bi bi-house-fill"></i> Dashboard
    </a>
    <a href="pengaturan.php" class="nav-link active">
      <i class="bi bi-gear-fill"></i> Pengaturan
    </a>
    <a href="laporan.php" class="nav-link">
      <i class="bi bi-bar-chart-fill"></i> Laporan
    </a>
    <a href="logout.php" class="nav-link logout-link">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </nav>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">

  <!-- Topbar -->
  <div class="topbar">
    <span class="topbar-title">
      <i class="bi bi-gear-fill me-2"></i> Pengaturan Hak Akses
    </span>
    <div class="d-flex align-items-center gap-3">
      <span class="text-secondary" style="font-size:14px">
        <i class="bi bi-person-circle me-1"></i>
        <?= htmlspecialchars($login_user) ?>
        <span class="badge ms-1 badge-admin">Admin</span>
      </span>
      <a href="logout.php" class="btn btn-outline-danger btn-sm">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>

  <div class="p-4">

    <div class="mb-4">
      <h5 class="fw-bold mb-0">Manajemen Hak Akses</h5>
      <small class="text-muted">Ubah status akun pengguna menjadi Admin atau User.</small>
    </div>

    <!-- Alert -->
    <?php if ($pesan_sukses): ?>
      <div class="alert alert-success py-2" style="font-size:13px">
        <i class="bi bi-check-circle me-1"></i> <?= $pesan_sukses ?>
      </div>
    <?php endif; ?>
    <?php if ($pesan_error): ?>
      <div class="alert alert-danger py-2" style="font-size:13px">
        <i class="bi bi-exclamation-circle me-1"></i> <?= $pesan_error ?>
      </div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="card-header-custom">
        <span class="fw-semibold" style="font-size:14px">
          <i class="bi bi-shield-lock-fill me-2"></i> Daftar Akun
        </span>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0 align-middle">
          <thead class="table-dark">
            <tr>
              <th>No</th>
              <th>Username</th>
              <th>Status Sekarang</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 6; while ($row = mysqli_fetch_assoc($query_users)): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td>
                <span class="badge <?= $row['status'] === 'Admin' ? 'badge-admin' : 'badge-user' ?>">
                  <?= $row['status'] ?>
                </span>
              </td>
              <td>
                <?php if ($row['status'] === 'User'): ?>
                  <a href="pengaturan.php?ubah_id=<?= $row['id'] ?>&status_baru=Admin"
                     class="btn btn-primary btn-sm"
                     onclick="return confirm('Jadikan <?= htmlspecialchars($row['username']) ?> sebagai Admin?')">
                    <i class="bi bi-arrow-up-circle-fill"></i> Jadikan Admin
                  </a>
                <?php else: ?>
                  <a href="pengaturan.php?ubah_id=<?= $row['id'] ?>&status_baru=User"
                     class="btn btn-secondary btn-sm"
                     onclick="return confirm('Turunkan <?= htmlspecialchars($row['username']) ?> menjadi User biasa?')">
                    <i class="bi bi-arrow-down-circle-fill"></i> Turunkan ke User
                  </a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>