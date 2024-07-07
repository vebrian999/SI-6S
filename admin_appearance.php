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
    // Jika pengguna bukan admin, arahkan ke halaman pengguna atau login
    header("Location: validasilogin.php");
    exit;
}

require_once 'koneksi.php'; // Koneksi ke database

// Tambah gambar banner baru
if(isset($_POST['tambah_banner'])) {
    $imagePath = "assets/img/new/" . $_FILES['gambar']['name'];
    $altText = $_POST['alt_text'];
    $isActive = $_POST['is_active'];

    // Simpan gambar ke server
    move_uploaded_file($_FILES['gambar']['tmp_name'], $imagePath);

    // Simpan detail gambar ke dalam database
    $sql = "INSERT INTO banners (image_path, alt_text, is_active) VALUES ('$imagePath', '$altText', '$isActive')";
    $result = $connection->query($sql);

    if ($result) {
        echo '<div id="notification" class="flex items-center justify-center bg-green-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
        echo '<i class="fas fa-trash-alt text-2xl mr-2"></i>';
        echo '<span class="text-lg">Gambar banner berhasil ditambahkan.</span>';
        echo '</div>';

        // Redirect to dashboard
// Redirect to dashboard after adding banner
header("Location: admin_appearance.php?success=add");
exit(); // Ensure no further output is sent

    } else {
        echo '<div id="notification" class="flex items-center justify-center bg-red-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
        echo '<i class="fas fa-trash-alt text-2xl mr-2"></i>';
        echo '<span class="text-lg">Terjadi kesalahan saat menambahkan gambar banner.</span>';
        echo '</div>';
    }
}


// Hapus gambar banner
if(isset($_GET['hapus_banner'])) {
    $id = $_GET['hapus_banner'];

    // Dapatkan path gambar dari database
    $sql = "SELECT image_path FROM banners WHERE id = '$id'";
    $result = $connection->query($sql);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = $row['image_path'];

        // Hapus gambar dari server
        unlink($imagePath);

        // Hapus entri dari database
        $sql_delete = "DELETE FROM banners WHERE id = '$id'";
        $result_delete = $connection->query($sql_delete);


if ($result_delete) {
    echo '<div id="notification" class="flex items-center justify-center bg-red-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50 animate__animated animate__fadeInUp">';
    echo '<i class="fas fa-trash-alt text-2xl mr-2"></i>';
    echo '<span class="text-lg">Gambar banner berhasil dihapus.</span>';
    echo '</div>';
} else {
    echo '<div id="notification" class="flex items-center justify-center bg-red-900 text-white p-4 fixed bottom-0 left-0 right-0 z-50 animate__animated animate__fadeInUp">';
    echo '<i class="fas fa-trash-alt text-2xl mr-2"></i>';
    echo '<span class="text-lg">Terjadi kesalahan saat menghapus gambar banner.</span>';
    echo '</div>';
}


    }
}

// Ganti status gambar banner
if(isset($_POST['is_active'])) {
    $id = $_POST['id'];
    $isActive = $_POST['is_active'];

    // Perbarui status di database
    $sql = "UPDATE banners SET is_active = '$isActive' WHERE id = '$id'";
    $result = $connection->query($sql);

    if($result) {
        echo '<div id="notification" class="flex items-center justify-center bg-green-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
        echo '<i class="fas fa-check-circle text-2xl mr-2"></i>';
        echo '<span class="text-lg">Status gambar banner berhasil diperbarui.</span>';
        echo '</div>';
    } else {
        echo '<div id="notification" class="flex items-center justify-center bg-red-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
        echo '<i class="fas fa-exclamation-circle text-2xl mr-2"></i>';
        echo '<span class="text-lg">Terjadi kesalahan saat memperbarui status gambar banner.</span>';
        echo '</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin 6Second</title>
    <link href="output.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/app.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/your-embed-code.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    

<header>
            <?php
        require_once 'admin_header.php';
        ?>
</header>


<!-- Form untuk menambah gambar banner baru -->

<main>
    <div id="content" class="content">
        <div class="p-4 sm:ml-64 md:mt-10 mt-10">
            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg mt-12">
                <div class="grid grid-cols-1 mb-4">
                    <div class="flex items-center justify-start gap-2 md:h-28 rounded bg-white">
                        <i class="fa-solid fa-image md:text-7xl text-blue-600"></i>
                        <p class="md:text-7xl text-blue-600">Appearance</p>
                    </div>
                </div>
                <div class="mb-4 rounded bg-gray-50 ">

                <form action="" method="post" enctype="multipart/form-data" class="my-4">
                    <div class="md:flex items-center space-x-4">
                        <div class="flex-grow">
                            <label for="gambar" class="block font-medium text-gray-700">Gambar:</label>
                            <input type="file" name="gambar" id="gambar" accept="image/*" required class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full">
                        </div>
                        <div class="flex-grow">
                            <label for="alt_text" class="block font-medium text-gray-700">Judul</label>
                            <input type="text" name="alt_text" id="alt_text" placeholder="Alt Text" required class="mt-1 px-2 py-3 border border-gray-300 rounded-md w-full">
                        </div>
                        <div class="flex-grow">
                            <label for="is_active" class="block font-medium text-gray-700">Status:</label>
                            <select name="is_active" id="is_active" class="mt-1 px-2 py-3 border border-gray-300 rounded-md w-full">
                                <option value="1" selected>Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="flex-none">
                            <button type="submit" name="tambah_banner" class="inline-block px-4 py-3.5 mt-7 text-sm font-medium leading-5 text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring-blue active:bg-blue-700 transition duration-150 ease-in-out rounded-md">Tambah Banner</button>
                        </div>
                    </div>
                </form>



                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-black bg-white border-b-8 border-gray-200 shadow-xl">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2" />
                                            <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3">id</th>
                                    <th scope="col" class="px-6 py-3">Photo</th>
                                    <th scope="col" class="px-6 py-3">Image Path</th>
                                    <th scope="col" class="px-6 py-3">Judul photo</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $sql = "SELECT * FROM banners";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='w-4 p-4'>";
            echo "<div class='flex items-center '>";
            echo "<input id='checkbox-table-search-{$row['id']}' type='checkbox' class='w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2' />";
            echo "<label for='checkbox-table-search-{$row['id']}' class='sr-only'>checkbox</label>";
            echo "</div>";
            echo "</td>";
            echo "<td class='px-6 py-4 text-gray-500'>{$row['id']}</td>";
            echo "<td class='px-10 py-4'>";
            echo "<div class='w-24 h-24 overflow-hidden'>";
            echo "<img class='w-full h-full object-cover' src='{$row['image_path']}' alt='Photo Produk' />";
            echo "</div>";
            echo "</td>";
            echo "<td class='px-6 py-4 text-gray-500'>{$row['image_path']}</td>";
            echo "<td class='px-6 py-4 text-gray-500'>{$row['alt_text']}</td>";
            echo "<td class='px-6 py-4 text-gray-500'>";
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='id' value='{$row['id']}' />";
            echo "<select name='is_active' onchange='this.form.submit()' class='px-2 py-1 border border-gray-300 rounded-md'>";
            echo "<option value='1' " . ($row['is_active'] == 1 ? "selected" : "") . ">Aktif</option>";
            echo "<option value='0' " . ($row['is_active'] == 0 ? "selected" : "") . ">Tidak Aktif</option>";
            echo "</select>";
            echo "</form>";
            echo "</td>";

            echo "<td class='flex items-center justify-center px-6 py-4 md:mt-7'>";
            echo "<a href='?edit_banner={$row['id']}' class='flex items-center justify-center gap-x-2 font-medium bg-green-600 rounded-lg text-white hover:bg-green-700 px-7 py-2.5 hover:underline'>";
            echo "<i class='fa-solid fa-pencil'></i>";
            echo "Edit/Save";
            echo "</a>";
            echo "<a href='?hapus_banner={$row['id']}' class='flex items-center justify-center gap-x-2 font-medium bg-red-600 rounded-lg text-white hover:bg-red-700 px-7 py-2.5 hover:underline ms-3'>";
            echo "<i class='fa-solid fa-trash-can'></i>";
            echo "Remove";
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

<!-- script notifikasi -->

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