<?php
session_start();
require_once 'koneksi.php';

// Periksa apakah pengguna telah login atau belum
if (!isset($_SESSION['username'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit;
}

// Ambil data gambar dari database
$sql = "SELECT * FROM items"; // Misalnya kita ingin menampilkan hanya gambar yang diunggah oleh pengguna yang sedang login
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link href="./output.css" rel="stylesheet">
</head>

<body class="">
    <div class="">
        <?php require_once 'header.php'; ?>

        <div class="px-12">
            <p class="text-blue font-semibold text-3xl mt-10 mb-4 ml-8 px-12">Katalog Fashion</p>

            <?php
            // Periksa apakah parameter id telah diberikan di URL
            if (isset($_GET['id'])) {
                // Ambil nilai id dari URL
                $id = $_GET['id'];

                // Query untuk mengambil detail produk berdasarkan ID
                $query = "SELECT * FROM items WHERE id = $id";
                $result = $connection->query($query);

                // Periksa apakah hasil query mengembalikan baris
                if ($result->num_rows > 0) {
                    // Tampilkan detail produk
                    $row = $result->fetch_assoc();
                    echo '<img src="' . $row['image_path'] . '" alt="' . $row['description'] . '" style="width: 400px;">';
                } else {
                    // Jika tidak ada produk dengan ID yang sesuai, tampilkan pesan kesalahan
                    echo "Produk tidak ditemukan.";
                }
            } else {
                // Jika parameter id tidak diberikan di URL, tampilkan pesan kesalahan
                echo "Parameter id tidak diberikan.";
            }
            ?>
        </div>
    </div>

    

    <?php $connection->close(); ?>

    <div class="mt-4">
        <button id="logoutButton" class="fixed bottom-0 right-0 m-4 px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:shadow-outline-red active:bg-red-800">
            Logout
        </button>

        <script>
            document.getElementById('logoutButton').addEventListener('click', function() {
                var confirmLogout = confirm('Apakah Anda yakin ingin logout?');

                if (confirmLogout) {
                    // Redirect to the logout.php page
                    window.location.href = 'fungsiLogout.php';
                }
            });
        </script>
    </div>
</body>

</html>
