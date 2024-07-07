<!-- update hapus product yang ada komentar dan like nya -->

<?php

require_once 'koneksi.php'; // Koneksi ke database

session_start();


// Fungsi untuk memotong deskripsi menjadi maksimal 6 kata
function shortenDescription($description) {
    $words = explode(' ', $description);
    if (count($words) > 6) {
        return implode(' ', array_slice($words, 0, 6)) . '...';
    } else {
        return $description;
    }
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['role'] != 'admin') {
    header("Location: validasilogin.php");
    exit;
}

// Hitung jumlah total barang
$sql_count = "SELECT COUNT(*) AS total_products FROM detail_barang";
$result_count = $connection->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_products = $row_count['total_products'];

// Hapus barang
if (isset($_GET['hapus_product'])) {
    $id = $_GET['hapus_product'];

    // Hapus entri dari database
    $sql_delete = "DELETE FROM detail_barang WHERE id = '$id'";
    $result_delete = $connection->query($sql_delete);

    if ($result_delete) {
        echo '<div id="notification" class="flex items-center justify-center bg-green-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
        echo '<i class="fas fa-check-circle text-2xl mr-2"></i>';
        echo '<span class="text-lg">Barang berhasil dihapus.</span>';
        echo '</div>';
        header("Location: admin_product.php");
        exit(); // Ensure no further output is sent

    } else {
        echo '<div id="notification" class="flex items-center justify-center bg-red-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
        echo '<i class="fas fa-times-circle text-2xl mr-2"></i>';
        echo '<span class="text-lg">Terjadi kesalahan saat menghapus barang.</span>';
        echo '</div>';
    }
}

if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $nama_barang = $connection->real_escape_string($_POST['nama_barang']);
    $harga_barang = $connection->real_escape_string($_POST['harga_barang']);
    $nomor_telepon = $connection->real_escape_string($_POST['nomor_telepon']);
    $kondisi = $connection->real_escape_string($_POST['kondisi']);
    $deskripsi_barang = $connection->real_escape_string($_POST['deskripsi_barang']);
    $link_maps = $connection->real_escape_string($_POST['link_maps']);
    $lokasi_barang = $connection->real_escape_string($_POST['lokasi_barang']);
    $tanggal_masuk = $connection->real_escape_string($_POST['tanggal_masuk']);
    $jumlah_like = $connection->real_escape_string($_POST['jumlah_like']);
    $jumlah_views = $connection->real_escape_string($_POST['jumlah_views']);


    $gambar_barang_1 = $_FILES['gambar_barang_1']['name'];
    $gambar_barang_2 = $_FILES['gambar_barang_2']['name'];
    $gambar_barang_3 = $_FILES['gambar_barang_3']['name'];
    $gambar_barang_4 = $_FILES['gambar_barang_4']['name'];

// Paths to upload the images
$target_dir = "uploads/";
$target_file1 = $target_dir . basename($gambar_barang_1);
$target_file2 = $target_dir . basename($gambar_barang_2);
$target_file3 = $target_dir . basename($gambar_barang_3);
$target_file4 = $target_dir . basename($gambar_barang_4);

// Move uploaded files to the target directory
if ($gambar_barang_1) {
    $target_file1 = $target_dir . "gambar_barang_1.png"; // Ubah nama file sesuai kebutuhan
    move_uploaded_file($_FILES['gambar_barang_1']['tmp_name'], $target_file1);
}
if ($gambar_barang_2) {
    $target_file2 = $target_dir . "gambar_barang_2.png"; // Ubah nama file sesuai kebutuhan
    move_uploaded_file($_FILES['gambar_barang_2']['tmp_name'], $target_file2);
}
if ($gambar_barang_3) {
    $target_file3 = $target_dir . "gambar_barang_3.png"; // Ubah nama file sesuai kebutuhan
    move_uploaded_file($_FILES['gambar_barang_3']['tmp_name'], $target_file3);
}
if ($gambar_barang_4) {
    $target_file4 = $target_dir . "gambar_barang_4.png"; // Ubah nama file sesuai kebutuhan
    move_uploaded_file($_FILES['gambar_barang_4']['tmp_name'], $target_file4);
}

// Update data ke database
$sql_update = "UPDATE detail_barang SET 
    nama_barang = '$nama_barang', 
    harga_barang = '$harga_barang', 
    nomor_telepon = '$nomor_telepon', 
    kondisi = '$kondisi', 
    deskripsi_barang = '$deskripsi_barang', 
    link_maps = '$link_maps', 
    lokasi_barang = '$lokasi_barang',
    tanggal_masuk = '$tanggal_masuk',
    jumlah_like = '$jumlah_like',
    jumlah_views = '$jumlah_views'";

// Jika file gambar diunggah, salin file tersebut dan perbarui nama file di database
if (!empty($_FILES['gambar_barang_1']['name'])) {
    move_uploaded_file($_FILES['gambar_barang_1']['tmp_name'], $target_file1);
    $sql_update .= ", gambar_barang_1 = '$target_file1'";
}
if (!empty($_FILES['gambar_barang_2']['name'])) {
    move_uploaded_file($_FILES['gambar_barang_2']['tmp_name'], $target_file2);
    $sql_update .= ", gambar_barang_2 = '$target_file2'";
}
if (!empty($_FILES['gambar_barang_3']['name'])) {
    move_uploaded_file($_FILES['gambar_barang_3']['tmp_name'], $target_file3);
    $sql_update .= ", gambar_barang_3 = '$target_file3'";
}
if (!empty($_FILES['gambar_barang_4']['name'])) {
    move_uploaded_file($_FILES['gambar_barang_4']['tmp_name'], $target_file4);
    $sql_update .= ", gambar_barang_4 = '$target_file4'";
}

$sql_update .= " WHERE id = '$id'";



    $result_update = $connection->query($sql_update);

if ($result_update) {
    echo '<div id="notification" class="flex items-center justify-center bg-green-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
    echo '<i class="fas fa-check-circle text-2xl mr-2"></i>';
    echo '<span class="text-lg">Informasi barang berhasil diperbarui.</span>';
    echo '</div>';
    
    // Redirect to the same page after successful update
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
} else {
    // Handle update error
    echo '<div id="notification" class="flex items-center justify-center bg-red-500 text-white p-4 fixed bottom-0 left-0 right-0 z-50">';
    echo '<i class="fas fa-times-circle text-2xl mr-2"></i>';
    echo '<span class="text-lg">Terjadi kesalahan saat memperbarui informasi barang.</span>';
    echo '</div>';
    echo 'Error: ' . $connection->error;
}

// Clear the POST data to prevent resubmission
$_POST = array();



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
            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg mt-12">
                <div class="grid grid-cols-1 mb-4">
                    <div class="flex items-center justify-start gap-2 md:h-28 rounded bg-white">
                        <i class="fa-solid fa-cubes md:text-7xl text-blue-600"></i>
                        <p class="md:text-7xl text-blue-600">Products</p>
                        <div class="bg-blue-600 py-2 px-8">
                            <p class="text-white md:text-7xl"><?php echo $total_products; ?></p>
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
                            <input type="text" id="simple-search" placeholder="Search products" required="" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 py-3" />
                        </div>
                        <button type="submit" class="md:px-20 py-3 bg-blue-600 text-white md:text-base rounded-lg ml-2 hover:bg-blue-700 focus:outline-none focus:bg-blue-700">Search</button>
                    </form>

<?php if (isset($_POST['edit_product'])) : ?>
<form action="" method="post" enctype="multipart/form-data" class="p-4 border border-gray-200 rounded-lg">
    <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
    <div class="grid grid-cols-2 gap-4">
        <!-- Fields for product details (same as before) -->
        <div>
            <label for="nama_barang" class="block font-medium text-gray-700">Nama Barang:</label>
            <input type="text" name="nama_barang" id="nama_barang" value="<?php echo $_POST['nama_barang']; ?>" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required>
        </div>
        <div>
            <label for="harga_barang" class="block font-medium text-gray-700">Harga Barang:</label>
            <input type="text" name="harga_barang" id="harga_barang" value="<?php echo $_POST['harga_barang']; ?>" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <label for="nomor_telepon" class="block font-medium text-gray-700">Nomor Telepon:</label>
            <input type="text" name="nomor_telepon" id="nomor_telepon" value="<?php echo $_POST['nomor_telepon']; ?>" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required>
        </div>
        <div>
            <label for="kondisi" class="block font-medium text-gray-700">Kondisi:</label>
            <input type="text" name="kondisi" id="kondisi" value="<?php echo $_POST['kondisi']; ?>" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required>
        </div>
    </div>
    <div class="mt-4">
        <label for="deskripsi_barang" class="block font-medium text-gray-700">Deskripsi Barang:</label>
        <textarea name="deskripsi_barang" id="deskripsi_barang" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required><?php echo $_POST['deskripsi_barang']; ?></textarea>
    </div>
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <label for="link_maps" class="block font-medium text-gray-700">Link Maps:</label>
            <input type="text" name="link_maps" id="link_maps" value="<?php echo $_POST['link_maps']; ?>" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required>
        </div>
        <div>
            <label for="lokasi_barang" class="block font-medium text-gray-700">Lokasi Barang:</label>
            <input type="text" name="lokasi_barang" id="lokasi_barang" value="<?php echo $_POST['lokasi_barang']; ?>" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <label for="gambar_barang_1" class="block font-medium text-gray-700">Gambar Barang 1:</label>
            <input type="file" name="gambar_barang_1" id="gambar_barang_1" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full">
        </div>
        <div>
            <label for="gambar_barang_2" class="block font-medium text-gray-700">Gambar Barang 2:</label>
            <input type="file" name="gambar_barang_2" id="gambar_barang_2" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <label for="gambar_barang_3" class="block font-medium text-gray-700">Gambar Barang 3:</label>
            <input type="file" name="gambar_barang_3" id="gambar_barang_3" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full">
        </div>
        <div>
            <label for="gambar_barang_4" class="block font-medium text-gray-700">Gambar Barang 4:</label>
            <input type="file" name="gambar_barang_4" id="gambar_barang_4" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full">
        </div>
    </div>

<div class="grid grid-cols-2 gap-4 mt-4">
<div>
    <label for="tanggal_masuk" class="block font-medium text-gray-700">Tanggal Masuk:</label>
    <input type="date" name="tanggal_masuk" id="tanggal_masuk" value="<?php echo isset($row['tanggal_masuk']) ? $row['tanggal_masuk'] : ''; ?>" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full">
</div>

    <div>
        <label for="jumlah_like" class="block font-medium text-gray-700">Jumlah Like:</label>
        <input type="number" name="jumlah_like" id="jumlah_like" value="<?php echo isset($_POST['jumlah_like']) ? $_POST['jumlah_like'] : ''; ?>" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required>
    </div>
</div>
<div class="grid grid-cols-2 gap-4 mt-4">
    <div>
        <label for="jumlah_views" class="block font-medium text-gray-700">Jumlah Views:</label>
        <input type="number" name="jumlah_views" id="jumlah_views" value="<?php echo isset($_POST['jumlah_views']) ? $_POST['jumlah_views'] : ''; ?>" class="mt-1 px-2 py-1 border border-gray-300 rounded-md w-full" required>
    </div>
</div>




<!-- ini button submit -->
    <div class="mt-4 flex justify-end">
        <button type="submit" name="update_product" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">Update</button>
    </div>
</form>
<?php endif; ?>


                    <div class="overflow-x-auto mt-6">
                        <table class="w-full text-sm text-left text-gray-500">
                           <thead class="text-xs text-gray-700 uppercase bg-gray-50">
    <tr>
        <th scope="col" class="px-6 py-3">ID</th>
        <th scope="col" class="px-6 py-3">username</th>
        <th scope="col" class="px-6 py-3">Nama Barang</th>
        <th scope="col" class="px-6 py-3">Harga Barang</th>
        <th scope="col" class="px-6 py-3">Nomor Telepon</th>
        <th scope="col" class="px-20 py-3">Kondisi</th>
        <th scope="col" class="px-40 py-3">Deskripsi Barang</th>
        <th scope="col" class="px-6 py-3">Link Maps</th>
        <th scope="col" class="px-6 py-3">Lokasi Barang</th>
        <th scope="col" class="px-6 py-3">tanggal_masuk</th>
        <th scope="col" class="px-6 py-3">jumlah_like</th>
        <th scope="col" class="px-6 py-3">jumlah_views</th>
        <th scope="col" class="px-6 py-3">Gambar 1</th>
        <th scope="col" class="px-6 py-3">Gambar 2</th>
        <th scope="col" class="px-6 py-3">Gambar 3</th>
        <th scope="col" class="px-6 py-3">Gambar 4</th>
        <th scope="col" class="px-6 py-3">Actions</th>
    </tr>
</thead>

                                <tbody>
                                <?php
                                $sql = "SELECT * FROM detail_barang";
                                $result = $connection->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr class="bg-white border-b">';
                                    echo '<td class="px-6 py-4">' . $row['id'] . '</td>';
                                    echo '<td class="px-6 py-4">' . $row['username'] . '</td>';
echo '<td class="px-6 py-4">' . shortenDescription($row['nama_barang']) . '</td>';
echo '<td class="px-6 py-4">' . $row['harga_barang'] . '</td>';
echo '<td class="px-6 py-4">' . $row['nomor_telepon'] . '</td>';
echo '<td class="px-6 py-4">' . shortenDescription($row['kondisi']) . '</td>';
echo '<td class="px-6 py-4">' . shortenDescription($row['deskripsi_barang']) . '</td>';
echo '<td class="px-6 py-4">' . $row['link_maps'] . '</td>';
echo '<td class="px-6 py-4">' . $row['lokasi_barang'] . '</td>';
echo '<td class="px-6 py-4">' . $row['tanggal_masuk'] . '</td>';
echo '<td class="px-6 py-4">' . $row['jumlah_like'] . '</td>';
echo '<td class="px-6 py-4">' . $row['jumlah_views'] . '</td>';
echo '<td class="px-6 py-4"><img src="' . $row['gambar_barang_1'] . '" alt="Gambar 1" class="w-20 h-20 object-cover" style="width:100px; height:100px;"></td>';
echo '<td class="px-6 py-4"><img src="' . $row['gambar_barang_2'] . '" alt="Gambar 2" class="w-20 h-20 object-cover" style="width:100px; height:100px;"></td>';
echo '<td class="px-6 py-4"><img src="' . $row['gambar_barang_3'] . '" alt="Gambar 3" class="w-20 h-20 object-cover" style="width:100px; height:100px;"></td>';
echo '<td class="px-6 py-4"><img src="' . $row['gambar_barang_4'] . '" alt="Gambar 4" class="w-20 h-20 object-cover" style="width:100px; height:100px;"></td>';
echo '<td class="px-6 py-4 flex gap-2">';
echo '<form action="" method="post">';
echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
echo '<!-- Tambahan input fields untuk menyimpan data -->';
echo '<input type="hidden" name="nama_barang" value="' . $row['nama_barang'] . '">';
echo '<input type="hidden" name="harga_barang" value="' . $row['harga_barang'] . '">';
echo '<input type="hidden" name="nomor_telepon" value="' . $row['nomor_telepon'] . '">';
echo '<input type="hidden" name="kondisi" value="' . $row['kondisi'] . '">';
echo '<input type="hidden" name="deskripsi_barang" value="' . $row['deskripsi_barang'] . '">';
echo '<input type="hidden" name="link_maps" value="' . $row['link_maps'] . '">';
echo '<input type="hidden" name="lokasi_barang" value="' . $row['lokasi_barang'] . '">';
echo '<input type="hidden" name="tanggal_masuk" value="' . $row['tanggal_masuk'] . '">';
echo '<input type="hidden" name="jumlah_like" value="' . $row['jumlah_like'] . '">';
echo '<input type="hidden" name="jumlah_views" value="' . $row['jumlah_views'] . '">';
echo '<input type="hidden" name="gambar_barang_1" value="' . $row['gambar_barang_1'] . '">';
echo '<input type="hidden" name="gambar_barang_2" value="' . $row['gambar_barang_2'] . '">';
echo '<input type="hidden" name="gambar_barang_3" value="' . $row['gambar_barang_3'] . '">';
echo '<input type="hidden" name="gambar_barang_4" value="' . $row['gambar_barang_4'] . '">';
echo '<button type="submit" name="edit_product" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded md:mt-8">Edit</button>';
echo '</form>';
echo '<a href="?hapus_product=' . $row['id'] . '" class="inline-block ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded md:mt-8 " onclick="return confirm(\'Apakah Anda yakin ingin menghapus produk ini?\')">Hapus</a>';
echo '</td>';
echo '</tr>';

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script>
    setTimeout(function() {
        var notification = document.getElementById('notification');
        if (notification) {
            notification.style.display = 'none';
        }
    }, 3000);
</script>
</body>
</html>
