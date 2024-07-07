<?php
session_start();
require_once 'koneksi.php';

// Periksa apakah pengguna telah login atau belum
if (!isset($_SESSION['username'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit;
}

// Periksa peran pengguna (nanti dihapus aja ini)
if ($_SESSION['role'] != 'user') {
    // Jika pengguna bukan admin, arahkan ke halaman pengguna atau login
    header("Location: validasilogin.php");
    exit;
}

// Ambil data gambar dari database
$sql = "SELECT * FROM items";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link href="./output.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/app.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/your-embed-code.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
</head>

<header>
            <?php require_once 'header.php'; ?>
</header>

<body class="">
    <div class="">


        <div class="">
            <!-- Banner -->
            <?php require_once 'banner.php'; ?>




<!-- kategori -->
            <?php require_once 'kategori_barang.php'; ?>



<!-- card product -->
<div class="">
    <?php
    require_once 'tampil_card_product.php';
   ?>
</div>


            <?php
            // if ($result->num_rows > 0) {
            //     echo '<div class="flex flex-wrap">';

            //     $count = 0;
            //     while ($row = $result->fetch_assoc()) {
            //         echo '<div class="sm:w-1/5 mb-8">';
            //         echo '<a href="detailProduct.php?id=' . $row['id'] . '">';
            //         echo '<img src="' . $row['image_path'] . '" alt="' . $row['description'] . '" style="width: 50%;">'; // Mengubah lebar gambar menjadi 50%
            //         echo '</a>';
            //         echo '<p class="text-gray-700 text-base">Deskripsi: ' . $row['description'] . '</p>';
            //         echo '<p class="text-gray-700 text-base">Uploaded by: ' . $row['uploaded_by'] . '</p>';
            //         echo '</div>';
            //         $count++;
            //         if ($count % 5 == 0) {
            //             echo '</div><div class="flex flex-wrap">';
            //         }
            //     }
            //     echo '</div>';
            // } else {
            //     echo "No images uploaded.";
            // }
            ?>
        </div>




        <?php $connection->close(); ?>
    </div>

    <div class="mt-4">
        <script>
            document.getElementById('logoutButton').addEventListener('click', function () {
                var confirmLogout = confirm('Apakah Anda yakin ingin logout?');
                if (confirmLogout) {
                    window.location.href = 'fungsiLogout.php';
                }
            });
        </script>
    </div>

        <!-- cdn flowbite -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

<footer>
            <?php require_once 'footer.php'; ?>
</footer>
</html>
