<?php
session_start();
include 'config/koneksi.php';

// Proteksi: harus login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$login_id     = $_SESSION['id'];
$login_user   = $_SESSION['username'];
$login_status = $_SESSION['status'];
$is_admin     = ($login_status === 'Admin');

$pesan_sukses = "";
$pesan_error  = "";

// ============================================================
// AKSI: TAMBAH USER (Admin only)
// ============================================================
if ($is_admin && isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    $new_username = trim($_POST['new_username']);
    $new_password = $_POST['new_password'];
    $new_status   = $_POST['new_status'];

    if (empty($new_username) || empty($new_password)) {
        $pesan_error = "Username dan password wajib diisi!";
    } elseif (!in_array($new_status, ['Admin', 'User'])) {
        $pesan_error = "Status tidak valid!";
    } else {
        $cek = mysqli_query($conn, "SELECT id FROM users WHERE username='".mysqli_real_escape_string($conn, $new_username)."'");
        if (mysqli_num_rows($cek) > 0) {
            $pesan_error = "Username sudah digunakan!";
        } else {
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            $ins  = mysqli_query($conn,
                "INSERT INTO users (username, password, status)
                 VALUES ('".mysqli_real_escape_string($conn, $new_username)."', '$hash', '".mysqli_real_escape_string($conn, $new_status)."')"
            );
            if ($ins) {
                $new_id = mysqli_insert_id($conn);
                mysqli_query($conn,
                    "INSERT INTO log_activity (user_id, username, aktivitas)
                     VALUES ('$new_id', '".mysqli_real_escape_string($conn, $new_username)."', 'Register')"
                );
                mysqli_query($conn,
                    "INSERT INTO log_activity (user_id, username, aktivitas)
                     VALUES ('$login_id', '$login_user', 'Tambah User: $new_username')"
                );
                $pesan_sukses = "User <strong>$new_username</strong> berhasil ditambahkan!";
            } else {
                $pesan_error = "Gagal menambahkan user!";
            }
        }
    }
}

// ============================================================
// AKSI: RESET PASSWORD
// ============================================================
if (isset($_POST['aksi']) && $_POST['aksi'] === 'reset_password') {
    $target_id       = (int) $_POST['target_id'];
    $password_baru   = $_POST['password_baru'];

    // Boleh: admin reset siapa saja, user hanya reset miliknya sendiri
    // Pengecualian: id=1 hanya bisa reset passwordnya sendiri (tidak bisa direset admin lain)
    $boleh = $is_admin || ($target_id === $login_id);
    $proteksi_admin_utama = ($target_id === 1 && $login_id !== 1);

    if (!$boleh) {
        $pesan_error = "Akses ditolak!";
    } elseif ($proteksi_admin_utama) {
        $pesan_error = "Password akun admin utama tidak bisa direset oleh akun lain!";
    } elseif (empty($password_baru)) {
        $pesan_error = "Password baru wajib diisi!";
    } else {
        $hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $upd  = mysqli_query($conn,
            "UPDATE users SET password='$hash' WHERE id='$target_id'"
        );
        if ($upd) {
            // Ambil username target untuk log
            $row_target = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM users WHERE id='$target_id'"));
            $target_username = $row_target['username'] ?? '-';
            mysqli_query($conn,
                "INSERT INTO log_activity (user_id, username, aktivitas)
                 VALUES ('$login_id', '$login_user', 'Reset Password: $target_username')"
            );
            $pesan_sukses = "Password berhasil direset!";
        } else {
            $pesan_error = "Gagal mereset password!";
        }
    }
}

// ============================================================
// AKSI: HAPUS USER (Admin only)
// ============================================================
if ($is_admin && isset($_GET['hapus'])) {
    $hapus_id = (int) $_GET['hapus'];
    if ($hapus_id === 1) {
        $pesan_error = "Akun admin utama tidak bisa dihapus!";
    } elseif ($hapus_id === $login_id) {
        $pesan_error = "Tidak bisa menghapus akun sendiri!";
    } else {
        $row_hapus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM users WHERE id='$hapus_id'"));
        $hapus_username = $row_hapus['username'] ?? '-';
        mysqli_query($conn, "DELETE FROM users WHERE id='$hapus_id'");
        mysqli_query($conn,
            "INSERT INTO log_activity (user_id, username, aktivitas)
             VALUES ('$login_id', '$login_user', 'Hapus User: $hapus_username')"
        );
        $pesan_sukses = "User <strong>$hapus_username</strong> berhasil dihapus!";
    }
}

// ============================================================
// AMBIL DATA USER
// ============================================================
if ($is_admin) {
    $query_users = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
} else {
    $query_users = mysqli_query($conn, "SELECT * FROM users WHERE id='$login_id'");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
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
    <a href="dashboard.php" class="nav-link active">
      <i class="bi bi-house-fill"></i> Dashboard
    </a>
    <?php if ($is_admin): ?>
    <a href="pengaturan.php" class="nav-link">
      <i class="bi bi-gear-fill"></i> Pengaturan
    </a>
    <?php endif; ?>
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
      <i class="bi bi-list me-2"></i> Dashboard
    </span>
    <div class="d-flex align-items-center gap-3">
      <span class="text-secondary" style="font-size:14px">
        <i class="bi bi-person-circle me-1"></i>
        <?= htmlspecialchars($login_user) ?>
        <span class="badge ms-1 <?= $is_admin ? 'badge-admin' : 'badge-user' ?>">
          <?= $login_status ?>
        </span>
      </span>
      <a href="logout.php" class="btn btn-outline-danger btn-sm">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>

  <!-- Konten -->
  <div class="p-4">

    <div class="mb-4">
      <h5 class="fw-bold mb-0">Selamat datang, <?= htmlspecialchars($login_user) ?>!</h5>
      <small class="text-muted">Kelola data pengguna dengan mudah.</small>
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

    <!-- Tabel Card -->
    <div class="card shadow-sm">
      <div class="card-header-custom">
        <span class="fw-semibold" style="font-size:14px">
          <i class="bi bi-people-fill me-2"></i> Data Users
        </span>
        <?php if ($is_admin): ?>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
          <i class="bi bi-plus-lg"></i> Tambah User
        </button>
        <?php endif; ?>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0 align-middle">
          <thead class="table-dark">
            <tr>
              <th>No</th>
              <th>Username</th>
              <th>Status</th>
              <th>Dibuat Pada</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($query_users)):
                $boleh_aksi = $is_admin || ($row['id'] == $login_id);
                $tgl = date('d M Y H:i', strtotime($row['created_at']));
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td>
                <span class="badge <?= $row['status'] === 'Admin' ? 'badge-admin' : 'badge-user' ?>">
                  <?= $row['status'] ?>
                </span>
              </td>
              <td><?= $tgl ?></td>
              <td>
                <?php if ($boleh_aksi): ?>
                  <!-- Tombol Reset Password (id=1 hanya bisa direset oleh dirinya sendiri) -->
                  <?php if (!($row['id'] == 1 && $login_id != 1)): ?>
                  <button class="btn btn-warning btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalReset"
                    data-id="<?= $row['id'] ?>"
                    data-username="<?= htmlspecialchars($row['username']) ?>">
                    <i class="bi bi-pencil-fill"></i>
                  </button>
                  <?php endif; ?>
                  <!-- Tombol Hapus (admin only, tidak bisa hapus id=1 dan diri sendiri) -->
                  <?php if ($is_admin && $row['id'] != 1 && $row['id'] != $login_id): ?>
                  <a href="dashboard.php?hapus=<?= $row['id'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('Hapus user <?= htmlspecialchars($row['username']) ?>?')">
                    <i class="bi bi-trash-fill"></i>
                  </a>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="text-muted" style="font-size:12px">—</span>
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

<!-- ===== MODAL TAMBAH USER ===== -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Tambah User</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <input type="hidden" name="aksi" value="tambah">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label" style="font-size:13px">Username</label>
            <input type="text" name="new_username" class="form-control form-control-sm" placeholder="Masukkan username" required>
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-size:13px">Password</label>
            <input type="password" name="new_password" class="form-control form-control-sm" placeholder="Masukkan password" required>
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-size:13px">Status</label>
            <select name="new_status" class="form-select form-select-sm">
              <option value="User">User</option>
              <option value="Admin">Admin</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ===== MODAL RESET PASSWORD ===== -->
<div class="modal fade" id="modalReset" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fw-bold"><i class="bi bi-key-fill me-2"></i>Reset Password</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <input type="hidden" name="aksi" value="reset_password">
        <input type="hidden" name="target_id" id="resetTargetId">
        <div class="modal-body">
          <p style="font-size:13px">Reset password untuk akun: <strong id="resetUsername"></strong></p>
          <div class="mb-3">
            <label class="form-label" style="font-size:13px">Password Baru</label>
            <input type="password" name="password_baru" class="form-control form-control-sm" placeholder="Masukkan password baru" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning btn-sm">Reset</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Isi data modal reset password
const modalReset = document.getElementById('modalReset');
modalReset.addEventListener('show.bs.modal', function (e) {
  const btn = e.relatedTarget;
  document.getElementById('resetTargetId').value = btn.getAttribute('data-id');
  document.getElementById('resetUsername').textContent = btn.getAttribute('data-username');
});
</script>

</body>
</html>