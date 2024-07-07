<?php
require_once 'koneksi.php'; // Koneksi ke database

session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan pengguna ke halaman login
    header("Location: login.php");
    exit;
}

// Periksa peran pengguna (admin)
if ($_SESSION['role'] != 'admin') {
    // Jika pengguna bukan admin, arahkan ke halaman validasi login
    header("Location: validasilogin.php");
    exit;
}

// Hitung jumlah total pengguna
$sql_count = "SELECT COUNT(*) AS total_users FROM users";
$result_count = $connection->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_users = $row_count['total_users'];

// Hapus pengguna
if(isset($_GET['hapus_user'])) {
    $id = $_GET['hapus_user'];

    // Hapus entri dari database
    $sql_delete = "DELETE FROM users WHERE id = '$id'";
    $result_delete = $connection->query($sql_delete);

    if ($result_delete) {
        echo '<div id="notification" class="flex items-center justify-center bg-green-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
        echo '<i class="fas fa-check-circle text-2xl mr-2"></i>';
        echo '<span class="text-lg">Pengguna berhasil dihapus.</span>';
        echo '</div>';
        header("Location: admin_user.php");
        exit(); // Ensure no further output is sent

    } else {
        echo '<div id="notification" class="flex items-center justify-center bg-red-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
        echo '<i class="fas fa-times-circle text-2xl mr-2"></i>';
        echo '<span class="text-lg">Terjadi kesalahan saat menghapus pengguna.</span>';
        echo '</div>';
    }
}

// Aksi Edit Pengguna
if(isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    // Update data ke database
    $sql_update = "UPDATE users SET username = '$username', phone_number = '$phone_number', address = '$address', role = '$role' WHERE id = '$id'";
    $result_update = $connection->query($sql_update);

    if ($result_update) {
        echo '<div id="notification" class="flex items-center justify-center bg-green-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
        echo '<i class="fas fa-check-circle text-2xl mr-2"></i>';
        echo '<span class="text-lg">Informasi pengguna berhasil diperbarui.</span>';
        echo '</div>';
    } else {
        echo '<div id="notification" class="flex items-center justify-center bg-red-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
        echo '<i class="fas fa-times-circle text-2xl mr-2"></i>';
        echo '<span class="text-lg">Terjadi kesalahan saat memperbarui informasi pengguna.</span>';
        echo '</div>';
    }
}

// Ambil ID pengguna yang ingin diedit dari parameter URL
if (isset($_GET['edit_user'])) {
    $edit_id = $_GET['edit_user'];

    // Query untuk mendapatkan informasi pengguna berdasarkan ID
    $sql_select = "SELECT * FROM users WHERE id = '$edit_id'";
    $result_select = $connection->query($sql_select);

    if ($result_select->num_rows > 0) {
        $user_data = $result_select->fetch_assoc();
    }
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
    <?php require_once 'admin_header.php'; ?>
</header>

<main>
    <div id="content" class="content">
        <div class="p-4 sm:ml-64 md:mt-10 mt-10">
            <div class="p-4 border-2  border-gray-200 border-dashed rounded-lg mt-12">
                <div class="grid grid-cols-1 mb-4">
                    <div class="flex items-center justify-start gap-2 md:h-28 rounded bg-white">
                        <i class="fa-solid fa-users md:text-7xl text-blue-600"></i>
                        <p class="md:text-7xl text-blue-600">Users</p>
                        <div class="bg-blue-600 py-2 px-8">
                            <p class="text-white md:text-7xl"><?php echo $total_users; ?></p>
                        </div>
                    </div>
                </div>
                <div class="mb-4 rounded bg-gray-50">

    <!-- search -->
              <form class="flex items-center my-7 md:gap-x-14">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                    </svg>
                  </div>
                  <input
                    type="text"
                    id="simple-search"
                    placeholder="Search users"
                    required=""
                    class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 py-3" />
                </div>
                <button type="submit" class="md:px-20 py-3 bg-blue-600 text-white md:text-base rounded-lg ml-2 hover:bg-blue-700 focus:outline-none focus:bg-blue-700">Search</button>
              </form>

                    <?php if (isset($user_data)) : ?>
                    <form action="" method="post" class="p-4 border border-gray-200 rounded-lg">
                        <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="username" class="block font-medium text-gray-700">Username:</label>
                                <input type="text" name="username" id="username" value="<?php echo $user_data['username']; ?>" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required>
                            </div>
                            <div>
                                <label for="phone_number" class="block font-medium text-gray-700">Phone Number:</label>
                                <input type="text" name="phone_number" id="phone_number" value="<?php echo $user_data['phone_number']; ?>" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="address" class="block font-medium text-gray-700">Address:</label>
                            <textarea name="address" id="address" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required><?php echo $user_data['address']; ?></textarea>
                        </div>
                        <div class="mt-4">
                            <label for="role" class="block font-medium text-gray-700">Role:</label>
                            <select name="role" id="role" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required>
                                <option value="admin" <?php echo ($user_data['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="user" <?php echo ($user_data['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                            </select>
                        </div>
                        <div class="mt-6">
                            <button type="submit" name="edit_user" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 focus:outline-none focus:bg-blue-500">Update</button>
                            <a href="admin_user.php" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:bg-gray-400">Cancel</a>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>

                <div class="mb-4 rounded bg-gray-50">
                    <!-- Existing User List -->
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-black bg-white border-b-8 border-gray-200 shadow-xl">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID</th>
                                    <th scope="col" class="px-6 py-3">Username</th>
                                    <th scope="col" class="px-6 py-3">Phone Number</th>
                                    <th scope="col" class="px-6 py-3">Address</th>
                                    <th scope="col" class="px-6 py-3">Role</th>
                                    <th scope="col" class="px-6 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM users";
                                $result = $connection->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='px-6 py-4 text-gray-500'>{$row['id']}</td>";
                                        echo "<td class='px-6 py-4 text-gray-500'>{$row['username']}</td>";
                                        echo "<td class='px-6 py-4 text-gray-500'>{$row['phone_number']}</td>";
                                        echo "<td class='px-6 py-4 text-gray-500'>{$row['address']}</td>";
                                        echo "<td class='px-6 py-4 text-gray-500'>{$row['role']}</td>";
                                        echo "<td class='flex items-center justify-center px-6 py-4'>";
                                        echo "<a href='?edit_user={$row['id']}' class='flex items-center justify-center gap-x-2 font-medium bg-green-600 rounded-lg text-white hover:bg-green-700 px-10 py-2.5 hover:underline'>";
                                        echo "<i class='fa-solid fa-pencil'></i> Edit";
                                        echo "</a>";
                                        echo "<a href='?hapus_user={$row['id']}' class='flex items-center justify-center gap-x-2 font-medium bg-red-600 rounded-lg text-white hover:bg-red-700 px-7 py-2.5 hover:underline ms-3'>";
                                        echo "<i class='fa-solid fa-trash-can'></i> Remove";
                                        echo "</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <aside>
        <?php require_once 'admin_aside_menu.php'; ?>
    </aside>
</main>

<script>
    // Setelah 5 detik, hilangkan pemberitahuan
    setTimeout(function() {
        var notification = document.getElementById('notification');
        notification.classList.remove('animate__fadeInUp');
        notification.classList.add('animate__fadeOutDown');
        setTimeout(function() {
            notification.style.display = 'none';
        }, 1000); // Sesuaikan dengan durasi animasi di CSS
    }, 5000);
</script>
</body>
</html>
