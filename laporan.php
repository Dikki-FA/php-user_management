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

// Filter
$filter_aktivitas = $_GET['aktivitas'] ?? '';
$filter_username  = trim($_GET['cari_username'] ?? '');

// Build query
$where = [];

if (!$is_admin) {
    $where[] = "user_id = '$login_id'";
}
if ($filter_aktivitas !== '') {
    $esc = mysqli_real_escape_string($conn, $filter_aktivitas);
    $where[] = "aktivitas LIKE '%$esc%'";
}
if ($filter_username !== '') {
    $esc = mysqli_real_escape_string($conn, $filter_username);
    $where[] = "username LIKE '%$esc%'";
}

$sql = "SELECT * FROM log_activity";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY waktu DESC";

$query_log = mysqli_query($conn, $sql);
$total     = mysqli_num_rows($query_log);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan</title>
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
    <?php if ($is_admin): ?>
    <a href="pengaturan.php" class="nav-link">
      <i class="bi bi-gear-fill"></i> Pengaturan
    </a>
    <?php endif; ?>
    <a href="laporan.php" class="nav-link active">
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
      <i class="bi bi-bar-chart-fill me-2"></i> Laporan Aktivitas
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

  <div class="p-4">

    <div class="mb-4">
      <h5 class="fw-bold mb-0">Log Aktivitas</h5>
      <small class="text-muted">
        <?= $is_admin ? 'Menampilkan semua aktivitas pengguna.' : 'Menampilkan aktivitas akun Anda.' ?>
      </small>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-3">
      <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
          <?php if ($is_admin): ?>
          <div class="col-auto">
            <label class="form-label mb-1" style="font-size:12px">Filter Aktivitas</label>
            <select name="aktivitas" class="form-select form-select-sm">
              <option value="">Semua</option>
              <option value="Login"         <?= $filter_aktivitas === 'Login'         ? 'selected' : '' ?>>Login</option>
              <option value="Logout"        <?= $filter_aktivitas === 'Logout'        ? 'selected' : '' ?>>Logout</option>
              <option value="Register"      <?= $filter_aktivitas === 'Register'      ? 'selected' : '' ?>>Register</option>
              <option value="Tambah User"   <?= $filter_aktivitas === 'Tambah User'   ? 'selected' : '' ?>>Tambah User</option>
              <option value="Reset Password"<?= $filter_aktivitas === 'Reset Password'? 'selected' : '' ?>>Reset Password</option>
              <option value="Ubah Status"   <?= $filter_aktivitas === 'Ubah Status'   ? 'selected' : '' ?>>Ubah Status</option>
              <option value="Hapus User"    <?= $filter_aktivitas === 'Hapus User'    ? 'selected' : '' ?>>Hapus User</option>
            </select>
          </div>
          <div class="col-auto">
            <label class="form-label mb-1" style="font-size:12px">Cari Username</label>
            <input type="text" name="cari_username" class="form-control form-control-sm"
                   placeholder="Cari username..." value="<?= htmlspecialchars($filter_username) ?>">
          </div>
          <?php endif; ?>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm">
              <i class="bi bi-search"></i> Cari
            </button>
            <a href="laporan.php" class="btn btn-secondary btn-sm ms-1">
              <i class="bi bi-x-circle"></i> Reset
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabel -->
    <div class="card shadow-sm">
      <div class="card-header-custom">
        <span class="fw-semibold" style="font-size:14px">
          <i class="bi bi-journal-text me-2"></i> Riwayat Aktivitas
        </span>
        <small class="text-muted"><?= $total ?> data</small>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0 align-middle">
          <thead class="table-dark">
            <tr>
              <th>No</th>
              <?php if ($is_admin): ?><th>Username</th><?php endif; ?>
              <th>Aktivitas</th>
              <th>Waktu</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($total === 0): ?>
              <tr>
                <td colspan="<?= $is_admin ? 4 : 3 ?>" class="text-center text-muted py-3">
                  Tidak ada data aktivitas.
                </td>
              </tr>
            <?php else: ?>
              <?php $no = 1; while ($row = mysqli_fetch_assoc($query_log)): ?>
              <tr>
                <td><?= $no++ ?></td>
                <?php if ($is_admin): ?>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <?php endif; ?>
                <td>
                  <?php
                  $akt = $row['aktivitas'];
                  $icon = 'bi-activity';
                  if (str_contains($akt, 'Login'))          $icon = 'bi-box-arrow-in-right text-success';
                  elseif (str_contains($akt, 'Logout'))     $icon = 'bi-box-arrow-right text-danger';
                  elseif (str_contains($akt, 'Register'))   $icon = 'bi-person-plus text-primary';
                  elseif (str_contains($akt, 'Tambah'))     $icon = 'bi-person-plus-fill text-primary';
                  elseif (str_contains($akt, 'Reset'))      $icon = 'bi-key-fill text-warning';
                  elseif (str_contains($akt, 'Ubah'))       $icon = 'bi-arrow-left-right text-info';
                  elseif (str_contains($akt, 'Hapus'))      $icon = 'bi-trash-fill text-danger';
                  ?>
                  <i class="bi <?= $icon ?> me-1"></i>
                  <?= htmlspecialchars($akt) ?>
                </td>
                <td style="font-size:12px; white-space:nowrap">
                  <?= date('d M Y H:i', strtotime($row['waktu'])) ?>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>