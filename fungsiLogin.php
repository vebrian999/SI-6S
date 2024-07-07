<?php
session_start();
require_once 'koneksi.php';

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Query untuk memeriksa kecocokan username di database
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $connection->query($sql);

if ($result->num_rows == 1) {
    // Ambil data user dari hasil query
    $user = $result->fetch_assoc();

    // Memeriksa kecocokan password menggunakan password_verify()
    if (password_verify($password, $user['password'])) {
        // Jika username dan password cocok, atur sesi dan redirect berdasarkan role
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
              header("location:admin_dashboard.php");
        } else {
            header('Location: validasilogin.php');
        }
        exit;
    } else {
        // Jika password tidak cocok, kembalikan ke halaman login dengan pesan error
        $_SESSION['error'] = 'Password salah';
        header('Location: login.php');
        exit;
    }
} else {
    // Jika username tidak ditemukan, kembalikan ke halaman login dengan pesan error
    $_SESSION['error'] = 'Username tidak ditemukan';
    header('Location: login.php');
    exit;
}

$connection->close();
