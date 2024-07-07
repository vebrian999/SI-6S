<?php
session_start();
require_once 'koneksi.php';

// Periksa apakah pengguna telah login atau belum
if (!isset($_SESSION['username'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit;
}

// Ambil username pengguna yang sedang login
$username = $_SESSION['username'];

// Menghapus produk jika delete_id dikirimkan melalui URL
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Query untuk menghapus produk berdasarkan ID dan username pengguna
    $delete_query = "DELETE FROM detail_barang WHERE id = $delete_id AND username = '$username'";
    
    if ($connection->query($delete_query) === TRUE) {
        // Redirect kembali ke halaman myproduct.php setelah berhasil menghapus
        header('Location: myproduct.php');
        exit;
    } else {
        // Handle error jika gagal menghapus
        echo 'Error deleting product: ' . $connection->error;
    }
}

// Query untuk mengambil daftar barang yang diunggah oleh pengguna
$query = "SELECT id, nama_barang, harga_barang, nomor_telepon, kondisi, deskripsi_barang, link_maps, lokasi_barang, tanggal_masuk, jumlah_like, jumlah_views, gambar_barang_1, gambar_barang_2, gambar_barang_3, gambar_barang_4 FROM detail_barang WHERE username = '$username'";
$result = $connection->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>6Second</title>
    <link rel="stylesheet" href="assets/app.css" />
    <link rel="stylesheet" href="output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <script src="https://use.fontawesome.com/your-embed-code.js"></script>

</head>

<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-white shadow">
        <!-- Tambahkan elemen header sesuai kebutuhan -->
    </header>

    <!-- Main Content -->
    <main class="container mx-auto py-8">
        <div class="px-6">
            
        <div class="font-medium md:text-5xl text-3xl text-sky-400 text-left flex gap-x-2 my-10">
<h2 class="">Product anda</h2>
 <i class="fa-solid fa-bag-shopping "></i>
        </div>
            <!-- Product Table -->
            <?php if ($result->num_rows > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border-collapse shadow-md rounded-md overflow-hidden">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="px-10 py-2 text-center">Product Name</th>
                                <th class="px-4 py-2 text-center">Price</th>
                                <th class="px-4 py-2 text-center">Phone Number</th>
                                <th class="px-20 py-2 text-center">Condition</th>
                                <th class="px-28 py-2 text-center">Description</th>
                                <th class="px-6 py-2 text-center w-full">Location Link</th>
                                <th class="px-4 py-2 text-center">Location</th>
                                <th class="px-20 py-2 text-center">Date</th>
                                <th class="px-4 py-2 text-center">Likes</th>
                                <th class="px-4 py-2 text-center">Views</th>
                                <th class="px-4 py-2 text-center">Image1</th>
                                <th class="px-4 py-2 text-center">Image2</th>
                                <th class="px-4 py-2 text-center">Image3</th>
                                <th class="px-4 py-2 text-center">Image4</th>
                                <th class="px-4 py-2 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-center">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['harga_barang']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['nomor_telepon']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['kondisi']); ?></td>
                                    <td class="px-4 py-2">
                                        <?php 
                                            // Mengambil deskripsi barang
                                            $deskripsi = htmlspecialchars($row['deskripsi_barang']);
                                            
                                            // Membatasi deskripsi agar tidak lebih dari 7 kata
                                            $words = explode(' ', $deskripsi);
                                            $shortened = implode(' ', array_slice($words, 0, 7));
                                            
                                            echo $shortened;
                                            if (count($words) > 7) {
                                                echo '...';
                                            }
                                        ?>
                                    </td>
                                    <td class="px-4 py-2"><a href="<?php echo htmlspecialchars($row['link_maps']); ?>" target="_blank" class="text-blue-500 underline">Maps Link</a></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['lokasi_barang']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['tanggal_masuk']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['jumlah_like']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['jumlah_views']); ?></td>
                                    <td class="px-4 py-2">
                                        <img src="<?php echo htmlspecialchars($row['gambar_barang_1']); ?>" alt="Product Image 1" class="h-12 w-auto">
                                    </td>
                                    <td class="px-4 py-2">
                                        <img src="<?php echo htmlspecialchars($row['gambar_barang_2']); ?>" alt="Product Image 2" class="h-12 w-auto">
                                    </td>
                                    <td class="px-4 py-2">
                                        <img src="<?php echo htmlspecialchars($row['gambar_barang_3']); ?>" alt="Product Image 3" class="h-12 w-auto">
                                    </td>
                                    <td class="px-4 py-2">
                                        <img src="<?php echo htmlspecialchars($row['gambar_barang_4']); ?>" alt="Product Image 4" class="h-12 w-auto">
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center space-x-4">
                                            <a href="edit_myproduct.php?id=<?php echo $row['id']; ?>" class="py-2 px-3 flex items-center text-sm font-medium text-center text-green-600 rounded-lg hover:bg-green-600 hover:text-white border border-green-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                </svg>
                                                Edit
                                            </a>
                                            <a href="tampil_detail_product.php?id=<?php echo $row['id']; ?>" class="py-2 px-3 flex items-center text-sm font-medium text-center text-blue-600 focus
bg-white rounded-lg border border-blue-600 hover
hover
focus
focus
focus
">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-2 -ml-0.5">
<path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" />
</svg>
Preview
</a>
<a href="myproduct.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')" class="flex items-center text-red-600 hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center ">
<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
</svg>
Delete
</a>
</div>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
<?php else: ?>
<p class="text-gray-600">Tidak ada product yang anda jual.</p>
<?php endif; ?>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
