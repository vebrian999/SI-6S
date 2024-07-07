<?php
// Periksa apakah sesi sudah aktif sebelum memulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'admin') {
    // Jika pengguna bukan admin, arahkan ke halaman pengguna atau login
    header("Location: validasilogin.php");
    exit;
}

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
    <title>Admin 6Second</title>
        <link href="/output.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/app.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/your-embed-code.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <header>
        <nav class="fixed bg-white top-0 z-50 w-full  border-b border-gray-200">
            <div class="px-3 py-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-start rtl:justify-end">
                        <button
                            data-drawer-target="logo-sidebar"
                            data-drawer-toggle="logo-sidebar"
                            aria-controls="logo-sidebar"
                            type="button"
                            class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    clip-rule="evenodd"
                                    fill-rule="evenodd"
                                    d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                            </svg>
                        </button>
                        <a href="admin_dashboard.php" class="flex ms-2 md:me-24">
                            <img src="assets/img/new/logo.png" class="h-16 me-3" alt="FlowBite Logo" />
                            <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-black">6SECOND</span>
                        </a>
                    </div>
                    <div class="flex items-center">
                        <div class="flex items-center ms-3">
                            <div>
                                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-12 h-12 rounded-full" src="<?php echo $profile_picture; ?>" alt="user photo" />
                                </button>
                            </div>
                            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow" id="dropdown-user">
                                <div class="px-4 py-3" role="none">
                                    <p class="text-lg text-gray-900" role="none">Selamat datang <?php echo htmlspecialchars($row['username']); ?>  &#128522;</p>
                                    <p class="text-sm font-medium text-blue-600 truncate"><?php echo htmlspecialchars($row['role']); ?></p>
                                </div>
                                <ul class="py-1" role="none">
                                    <li>
                                        <a href="admin_dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Dashboard</a>
                                    </li>
                                    <li>
                                        <a href="admin_profil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profil</a>
                                    </li>
                                    <li>
                                        <a href="#" id="logoutButton" class="block px-4 py-2 text-sm  hover:bg-gray-100 hover:text-red-700 text-red-600" role="menuitem">Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Script untuk mengelola dropdown dan logout -->
    <script src="assets/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
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
