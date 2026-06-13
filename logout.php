<?php
session_start();

$user_id  = $_SESSION['id']       ?? null;
$username = $_SESSION['username'] ?? null;

include 'config/koneksi.php';

if ($user_id && $username) {
    mysqli_query($conn,
        "INSERT INTO log_activity (user_id, username, aktivitas)
         VALUES ('$user_id', '$username', 'Logout')"
    );
}

session_destroy();
header("Location: login.php");
exit;
?>