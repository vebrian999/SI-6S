<?php
// Mulai sesi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Tindakan jika pengguna belum login, misalnya, arahkan pengguna ke halaman login
    header("Location: login.php");
    exit; // Pastikan untuk keluar setelah mengarahkan pengguna
}

require_once 'koneksi.php'; // Mengimpor file koneksi.php yang berisi informasi tentang koneksi ke database


if ($connection->connect_error) {
    die("Koneksi ke database gagal: " . $connection->connect_error);
}

// Ambil informasi pengguna dari database berdasarkan username yang disimpan di session
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $connection->query($sql);

// Tentukan foto profil default
$default_profile_picture = 'assets/img/new/Pro max pfp.jpg';

// Periksa apakah data pengguna ditemukan
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Periksa apakah ada foto profil dalam database
    if (!empty($row['profile_picture']) && file_exists($row['profile_picture'])) {
        $profile_picture = $row['profile_picture'];
    } else {
        // Jika tidak ada foto profil atau foto profil tidak valid, gunakan foto profil default
        $profile_picture = $default_profile_picture;
    }
} else {
    // Jika data pengguna tidak ditemukan, gunakan foto profil default
    $profile_picture = $default_profile_picture;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>6Second</title>
    <!-- <link rel="stylesheet" href="assets/app.css" /> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/your-embed-code.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<style>
    /* Default width for mobile */
    .search-input {
        width: 100%; 
    }

    /* Fixed width for desktop */
    @media (min-width: 768px) {
        .search-input {
            width: 400px; 
        }
    }
</style>

<body>
    <header>
    <nav class=" bg-white fixed w-full z-50 top-0 start-0 transition-all duration-500">
        <div class="flex max-w-screen-xl flex-wrap items-center justify-between mx-auto md:p-0 p-4">
            <div class="flex items-center space-x-3 rtl:space-x-reverse text-2xl font-semibold">
                <a href="dashboard.php" class="flex items-center space-x-3 rtl:space-x-reverse text-2xl font-semibold">
                    <img src="assets/img/new/logo.png" class="md:h-16 h-5" alt="Flowbite Logo" style="height: 64px; width: 64px;" />6SECOND
                </a>
            </div>
            <div class="flex items-center md:order-2 space-x-3 rtl:space-x-reverse">
                <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400" aria-controls="navbar-sticky" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
                                <div class="relative flex justify-end">
                            <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-white focus:outline-none font-medium rounded-lg text-sm py-2.5 text-center inline-flex items-center" type="button">
                                <img src="<?php echo $profile_picture; ?>" alt="Account" class="w-8 h-8 mr-2 rounded-full" />
                                <svg class="w-2.5 h-2.5 ml-1 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <div id="dropdown" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow">
                                <ul class="py-1 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                                    <li>
                                        <a href="halamanProfile.php" class="block py-2 px-4 hover:bg-gray-100">Profile</a>
                                    </li>
                                    <li>
                                        <a href="#" id="logoutButton" class="block py-2 px-4 hover:bg-gray-100 hover:text-red-700 text-red-600">Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
            </div>
            <div class="hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
<ul class="md:flex flex-col p-4 md:p-0 mt-4 bg-gray-50 items-center md:gap-x-0 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:bg-white">
    <li>
<form method="GET" action="hasilpencarian.php" class="relative flex items-center my-5 w-full md:w-auto">
    <div class="flex w-full ml-2">
        <label for="search-dropdown" class="mb-2 text-sm font-medium text-gray-900 sr-only">Your Email</label>
        <button id="dropdown-button2" data-dropdown-toggle="dropdown2" class="flex-shrink-0 z-10 inline-flex items-center font-normal py-2.5 px-4 text-base text-center text-gray-400 bg-white border border-gray-300 rounded-s-lg hover:bg-sky-400 hover:text-white" type="button">
            Kategori
            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
            </svg>
        </button>
        <div id="dropdown2" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
            <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdown-button2">
                <li>
                    <a href="Fashion.php"><button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100">Fashion</button></a>
                </li>
                <li>
                    <a href="elektronik.php"><button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100">Elektronik</button></a>
                </li>
                <li>
                    <a href="prabot.php"><button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100">Perabot</button></a>
                </li>
                <li>
                    <a href="otomotif.php"><button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100">Otomotif</button></a>
                </li>
            </ul>
        </div>
<div class="flex w-full">
    <input type="text" name="keyword" id="search-dropdown" class="search-input block p-2.5 flex-grow h-14  text-start text-base font-normal text-black bg-white border border-gray-300 focus:ring-blue-400 " placeholder="Kata Kunci..." required />
    <button type="submit" class="flex items-center p-2.5 text-sm font-medium h-full text-sky-400 bg-sky-400 rounded-r-lg border border-sky-400 hover:text-white hover:bg-sky-400 focus:ring-4 focus:outline-none focus:ring-sky-400">
        <svg class="w-6 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
        </svg>
        <span class="sr-only">Search</span>
    </button>
</div>
    </div>
</form>

    </li>
    <li class="my-8 md:my-0">
        <a href="tampilan_form_product.php" class="bg-sky-300 hover:bg-sky-600 py-2 px-7 text-white rounded-lg ">Jual Sekarang</a>
    </li>
    <li class="my-6 md:my-0">
        <a href="tampil_notifikasi.php" class="text-black hover:text-sky-300 rounded-full focus:outline-none flex md:gap-x-0 gap-x-3">
            <i class="fa-solid fa-bell text-2xl  md:block"></i> <!-- Tampilkan hanya di desktop -->
            <span class="block md:hidden">Notifikasi Anda</span> <!-- Tampilkan hanya di mobile -->
        </a>
    </li>
    <li class="my-6 md:my-0">
        <a href="favorite_product.php" class="text-black hover:text-red-600 rounded-full focus:outline-none relative group flex md:gap-x-0 gap-x-3 ">
            <i class="fa-solid fa-heart text-2xl  md:block"></i> <!-- Tampilkan hanya di desktop -->
            <span class="block md:hidden">Favorit</span> <!-- Tampilkan hanya di mobile -->
        </a>
    </li>
    <li class="my-6 md:my-0">
        <a href="myproduct.php" class="text-black hover:text-blue-600 -mt-1 rounded-full focus:outline-none relative group flex md:gap-x-0 gap-x-3">
        <i class="fa-solid fa-bag-shopping text-2xl md:block"></i>
        <span class="block md:hidden">Produk Saya</span> <!-- Tampilkan hanya di mobile -->
        </a>
    </li>
</ul>

            </div>

        </div>
    </nav>
</header>

    <!-- nav script -->
    <script src="assets/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>


    <!-- log out -->
    <script>
        document.getElementById('logoutButton').addEventListener('click', function () {
            var confirmLogout = confirm('Apakah Anda yakin ingin logout?');
            if (confirmLogout) {
                window.location.href = 'fungsiLogout.php';
            }
        });
    </script>
</body>
</html>
