<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan pengguna ke halaman login
    header("Location: login.php");
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'admin') {
    // Jika pengguna bukan admin, arahkan ke halaman validasi login
    header("Location: validasilogin.php");
    exit;
}

// Sambungkan ke database
require_once 'koneksi.php';

// Ambil jumlah pengguna
$sql_users = "SELECT COUNT(*) AS total_users FROM users";
$result_users = $connection->query($sql_users);
$total_users = $result_users->fetch_assoc()['total_users'];

// Ambil jumlah barang
$sql_barang = "SELECT COUNT(*) AS total_barang FROM detail_barang";
$result_barang = $connection->query($sql_barang);
$total_barang = $result_barang->fetch_assoc()['total_barang'];


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin 6Second</title>
    <link rel="stylesheet" href="assets/app.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/your-embed-code.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body>
    <header>
        <?php require_once 'admin_header.php'; ?>
    </header>

    <main>
        <div id="content" class="content">
            <div class="p-4 sm:ml-64 md:mt-10 mt-10">
                <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg mt-12">
                    <div class="grid  grid-cols-4 gap-4 mb-4">
                        <div class="flex items-center justify-center gap-2 md:h-32 rounded bg-blue-600">
                            <i class="fa-solid fa-users md:text-8xl text-white"></i>
                            <p class="md:text-8xl text-white"><?php echo $total_users; ?></p>
                        </div>
                        <div class="flex items-center justify-center gap-2 md:h-32 rounded bg-blue-600">
                            <i class="fa-solid fa-bag-shopping md:text-8xl text-white"></i>
                            <p class="md:text-8xl text-white"><?php echo $total_barang; ?></p>
                        </div>
                    </div>
            <div class="flex items-center justify-center h-full mb-4 rounded bg-gray-50">
              <canvas class="" id="myChart" width="400" height="150"></canvas>
              <script src="./assets/chart.js"></script>
            </div>
                </div>
            </div>
        </div>
        <aside>
            <?php require_once 'admin_aside_menu.php'; ?>
        </aside>
    </main>

    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
