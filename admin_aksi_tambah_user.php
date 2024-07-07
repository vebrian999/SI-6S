<?php
require_once 'koneksi.php'; // Koneksi ke database

// Inisialisasi variabel untuk menyimpan pesan error
$username_err = $password_err = $phone_number_err = $address_err = $role_err = $profile_picture_err = "";

// Proses data yang dikirim dari formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Username tidak boleh kosong.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validasi input password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Password tidak boleh kosong.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validasi input nomor telepon
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Nomor telepon tidak boleh kosong.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    // Validasi input alamat
    if (empty(trim($_POST["address"]))) {
        $address_err = "Alamat tidak boleh kosong.";
    } else {
        $address = trim($_POST["address"]);
    }

    // Validasi input peran (role)
    if (empty(trim($_POST["role"]))) {
        $role_err = "Peran tidak boleh kosong.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Validasi input foto profil
    if (empty($_FILES["profile_picture"]["name"])) {
        $profile_picture_err = "Foto profil tidak boleh kosong.";
    } else {
        $profile_picture = $_FILES["profile_picture"]["name"];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $profile_picture_err = "File bukan merupakan gambar.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $profile_picture_err = "Maaf, file sudah ada.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["profile_picture"]["size"] > 500000) {
            $profile_picture_err = "Maaf, ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $profile_picture_err = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $profile_picture_err = "Maaf, foto profil tidak dapat diunggah.";
        } else {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // Do nothing
            } else {
                $profile_picture_err = "Maaf, terjadi kesalahan saat mengunggah foto profil.";
            }
        }
    }

    // Jika tidak ada pesan error, masukkan data ke database
    if (empty($username_err) && empty($password_err) && empty($phone_number_err) && empty($address_err) && empty($role_err) && empty($profile_picture_err)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk menyimpan data ke database
        $sql = "INSERT INTO users (username, password, phone_number, address, role, profile_picture) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $connection->prepare($sql)) {
            // Bind parameter ke statement
            $stmt->bind_param("ssssss", $param_username, $param_password, $param_phone_number, $param_address, $param_role, $param_profile_picture);

            // Set parameter
            $param_username = $username;
            $param_password = $hashed_password;
            $param_phone_number = $phone_number;
            $param_address = $address;
            $param_role = $role;
            $param_profile_picture = $profile_picture;

            // Eksekusi statement
            if ($stmt->execute()) {
                // Redirect kembali ke halaman utama setelah berhasil menambahkan pengguna
                header("Location: index.php");
                exit;
            } else {
                echo "Terjadi kesalahan. Silakan coba lagi.";
            }

            // Tutup statement
            $stmt->close();
        }
    }

    // Tutup koneksi database
    $connection->close();
}
?>
