<?php
session_start();
require_once 'koneksi.php';

// Periksa apakah pengguna telah login atau belum
if (!isset($_SESSION['username'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit;
}

// Ambil username dan role dari sesi pengguna
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Periksa apakah file gambar telah dipilih untuk diunggah
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    // Tentukan direktori penyimpanan untuk gambar profil (pastikan direktori tersebut sudah ada)
    $upload_dir = 'uploads/';

    // Ambil informasi file gambar
    $file_name = $_FILES['profile_picture']['name'];
    $file_tmp = $_FILES['profile_picture']['tmp_name'];

    // Buat nama file unik untuk mencegah tumpang tindih nama
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $unique_file_name = $username . '_' . uniqid() . '.' . $file_extension;

    // Tentukan path lengkap untuk menyimpan file gambar
    $upload_path = $upload_dir . $unique_file_name;

    // Pindahkan file gambar ke direktori penyimpanan
    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Simpan path foto profil ke database
        $sql = "UPDATE users SET profile_picture = '$upload_path' WHERE username = '$username'";
        if ($connection->query($sql) === TRUE) {
            // Jika berhasil menyimpan path gambar ke database
            $_SESSION['success_message'] = "Foto profil berhasil diperbarui.";

            // Redirect ke halaman sesuai dengan peran pengguna
            if ($role === 'user') {
                header('Location: halamanProfile.php');
            } elseif ($role === 'admin') {
                header('Location: admin_profil.php');
            }
            exit;
        } else {
            $_SESSION['error_message'] = "Gagal menyimpan path foto profil ke database.";
        }
    } else {
        // Jika gagal memindahkan file
        $_SESSION['error_message'] = "Gagal mengunggah foto profil.";
    }
} else {
    // Jika tidak ada file yang diunggah atau terjadi kesalahan saat unggah
    $_SESSION['error_message'] = "Tidak ada file yang diunggah atau terjadi kesalahan.";
}

// Redirect ke halaman sesuai dengan peran pengguna
if ($role === 'user') {
    header('Location: halamanProfile.php');
} elseif ($role === 'admin') {
    header('Location: admin_profil.php');
}
exit;

$connection->close();
?>
