<?php
$host = 'localhost';
$db = '6second';
$user = 'root';
$pass = '';

$connection = new mysqli($host, $user, $pass, $db);

if ($connection->connect_error) {
    die("Koneksi ke database gagal: " . $connection->connect_error);
}
