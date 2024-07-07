<?php
// session_start();
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

<main>
        <!-- awal sidebar (aside) -->
      <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 mt-10 h-screen pt-20 transition-transform -translate-x-full bg-blue-600 border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-blue-600">
          <ul class="space-y-2 font-medium">
            <!-- dashbord -->
            <li>
              <a href="admin_dashboard.php" class="flex items-center p-2 text-white rounded-lg hover:bg-white hover:text-black">
                <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                  <path
                    d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                </svg>
                <span class="ms-3">Dashboard</span>
              </a>
            </li>

            <!-- product -->
            <li>
              <a href="admin_product.php" class="flex items-center p-2 text-white rounded-lg hover:bg-white hover:text-black">
                <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Products</span>
              </a>
            </li>

            <!-- users -->
            <li>
              <a href="admin_user.php" class="flex items-center p-2 text-white rounded-lg hover:bg-white hover:text-black">
                <svg class="flex-shrink-0 w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                  <path
                    d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
              </a>
            </li>

         

            <!-- user -->
            <li>
              <a href="admin_profil.php" class="flex items-center p-2 text-white rounded-lg hover:bg-white hover:text-black">
                <i class="fa-solid fa-user text-xl"></i>
                <span class="flex-1 ms-3 whitespace-nowrap">User</span>
              </a>
            </li>

            <!-- Appearance -->
            <li>
              <a href="admin_appearance.php" class="flex items-center p-2 text-white rounded-lg hover:bg-white hover:text-black">
                <i class="fa-solid fa-image text-xl"></i>
                <span class="flex-1 ms-3 whitespace-nowrap">Appearance</span>
              </a>
            </li>
          </ul>

          <ul class="space-y-2 mt-4 border-t border-gray-200 pt-4">
            <!-- log out -->
            <li class="">
            <li>
    <a href="fungsiLogout.php" id="logoutButton" class="flex items-center p-2 text-white rounded-lg hover:bg-white hover:text-red-600" role="menuitem">
        <i class="fa-solid fa-power-off text-2xl"></i>
        <span class="flex-1 ms-3 whitespace-nowrap">Log out</span>
    </a>
</li>

            </li>
          </ul>
        </div>
      </aside>
      <!-- akhir sidebar (aside) -->
</main>


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
