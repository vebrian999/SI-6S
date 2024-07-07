<?php
session_start();
require_once 'koneksi.php';

// Periksa apakah pengguna telah login atau belum
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

// Update the database to remove the profile picture
$sql = "UPDATE users SET profile_picture = '' WHERE username = '$username'";
if ($connection->query($sql) === TRUE) {
    // Optionally, you can also delete the photo file from the server if it's stored locally
    // $profile_picture_path = '/path/to/profile/pictures/' . $username . '.jpg';
    // if (file_exists($profile_picture_path)) {
    //     unlink($profile_picture_path);
    // }

    header('Location: halamanProfile.php');
    exit;
} else {
    echo "Error updating record: " . $connection->error;
}

$connection->close();
?>
