<?php
session_start();
require_once 'koneksi.php';

// Periksa apakah pengguna telah login atau belum
if (!isset($_SESSION['username'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit;
}

// Ambil informasi pengguna dari database berdasarkan username
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $connection->query($sql);

if ($result->num_rows == 1) {
    // Jika pengguna ditemukan, ambil informasi dan tampilkan di halaman profil
    $row = $result->fetch_assoc();
    $phone_number = $row['phone_number'];
    $address = $row['address'];
    $profile_picture = $row['profile_picture'];
} else {
    // Jika pengguna tidak ditemukan, berikan pesan error
    $error_message = "Informasi pengguna tidak ditemukan.";
}

// Periksa apakah formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Ambil nilai yang dikirim dari formulir
    $new_phone_number = $_POST["phone_number"];
    $new_address = $_POST["address"];
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_new_password = $_POST["confirm_new_password"];
    
    // Ambil username dari sesi pengguna
    $username = $_SESSION['username'];

    // Update informasi profil dalam database
    $sql = "UPDATE users SET phone_number='$new_phone_number', address='$new_address' WHERE username='$username'";
    if ($connection->query($sql) === TRUE) {
        if ($connection->affected_rows > 0) {
            $_SESSION['success_message'] = "Informasi profil berhasil diperbarui.";
        } else {
            $_SESSION['error_message'] = "Tidak ada perubahan yang dilakukan.";
        }
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan. Silakan coba lagi.";
    }

    // Periksa apakah pengguna ingin mengubah kata sandi
    if (!empty($current_password) && !empty($new_password) && !empty($confirm_new_password)) {
        // Verifikasi kata sandi saat ini
        $sql = "SELECT password FROM users WHERE username='$username'";
        $result = $connection->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($current_password, $row['password'])) {
                // Periksa apakah kata sandi baru dan konfirmasi kata sandi baru cocok
                if ($new_password === $confirm_new_password) {
                    // Hash kata sandi baru
                    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update kata sandi dalam database
                    $sql = "UPDATE users SET password='$new_password_hashed' WHERE username='$username'";
                    if ($connection->query($sql) === TRUE) {
                        if ($connection->affected_rows > 0) {
                            $_SESSION['success_message'] = "Kata sandi berhasil diperbarui.";
                        } else {
                            $_SESSION['error_message'] = "Tidak ada perubahan yang dilakukan pada kata sandi.";
                        }
                    } else {
                        $_SESSION['error_message'] = "Terjadi kesalahan saat memperbarui kata sandi.";
                    }
                } else {
                    $_SESSION['error_message'] = "Konfirmasi kata sandi baru tidak cocok.";
                }
            } else {
                $_SESSION['error_message'] = "Kata sandi saat ini salah.";
            }
        } else {
            $_SESSION['error_message'] = "Pengguna tidak ditemukan.";
        }
    }

    // Proses unggah foto profil
    if ($_FILES['profile_picture']['size'] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Periksa apakah file adalah gambar asli atau gambar palsu
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['error_message'] = "File yang diunggah bukan gambar.";
            $uploadOk = 0;
        }

        // Periksa ukuran file
        if ($_FILES["profile_picture"]["size"] > 500000) { // 500KB
            $_SESSION['error_message'] = "Maaf, file yang diunggah terlalu besar.";
            $uploadOk = 0;
        }

        // Izinkan format file tertentu
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $_SESSION['error_message'] = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
            $uploadOk = 0;
        }

        // Periksa apakah $uploadOk disetel ke 0 oleh kesalahan
        if ($uploadOk == 0) {
            $_SESSION['error_message'] = "Maaf, file Anda tidak diunggah.";
        } else {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // Simpan path foto profil ke database
                $profile_picture_path = $target_file;
                $update_sql = "UPDATE users SET profile_picture='$profile_picture_path' WHERE username='$username'";
                if ($connection->query($update_sql) === TRUE) {
                    $_SESSION['success_message'] = "Foto profil berhasil diperbarui.";
                } else {
                    $_SESSION['error_message'] = "Gagal menyimpan foto profil.";
                }
            } else {
                $_SESSION['error_message'] = "Maaf, terjadi kesalahan saat mengunggah file.";
            }
        }
    }

    // Redirect kembali ke halaman profil
    header('Location: halamanProfile.php');
    exit;
}

$connection->close();
?>
