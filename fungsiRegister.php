<?php
session_start();
require_once 'koneksi.php';


// Get form data
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$phone_number = $_POST['phone_number'];
$address = $_POST['address'];

// Insert data into database
$query = "INSERT INTO users (username, password, phone_number, address,role) VALUES ('$username', '$password', '$phone_number', '$address','user')";
$result = mysqli_query($connection, $query);

if ($result) {
    // Set pesan sukses
    $_SESSION['success_message'] = "";
    // Redirect ke halaman login setelah beberapa detik
    header("Location: validasiregister.php");
} else {
    // Set pesan error
    $_SESSION['error'] = "Gagal melakukan registrasi: " . mysqli_error($connection);
    header("Location: register.php");
    exit(); // Pastikan script berhenti di sini setelah diarahkan
}

// Close database connection
mysqli_close($connection);
