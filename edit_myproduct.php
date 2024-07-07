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

// Periksa apakah parameter id ada dan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Query untuk mengambil detail produk berdasarkan ID
    $query = "SELECT * FROM detail_barang WHERE id = $id AND username = '$username'";
    $result = $connection->query($query);
    
    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc();
    } else {
        // Jika produk tidak ditemukan, redirect kembali ke myproduct.php
        header('Location: myproduct.php');
        exit;
    }
} else {
    // Jika parameter id tidak ada atau tidak valid, redirect kembali ke myproduct.php
    header('Location: myproduct.php');
    exit;
}

// Handle form submission for updating product details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $nama_barang = $_POST['nama_barang'];
    $harga_barang = $_POST['harga_barang'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $kondisi = $_POST['kondisi'];
    $deskripsi_barang = $_POST['deskripsi_barang'];
    $link_maps = $_POST['link_maps'];
    $lokasi_barang = $_POST['lokasi_barang'];

    // Update query
    $update_query = "UPDATE detail_barang SET 
                    nama_barang = '$nama_barang',
                    harga_barang = '$harga_barang',
                    nomor_telepon = '$nomor_telepon',
                    kondisi = '$kondisi',
                    deskripsi_barang = '$deskripsi_barang',
                    link_maps = '$link_maps',
                    lokasi_barang = '$lokasi_barang'
                    WHERE id = $id";

    if ($connection->query($update_query) === TRUE) {
        // Redirect back to myproduct.php after successful update
        header('Location: myproduct.php');
        exit;
    } else {
        // Handle error scenario
        echo 'Error updating product: ' . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Product</title>
    <link rel="stylesheet" href="assets/app.css" />
    <link rel="stylesheet" href="output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/your-embed-code.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="hidden md:flex md:items-center md:space-x-4">
                    <h1 class="text-xl font-semibold text-gray-800">Edit Product</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600 text-sm">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" class="text-blue-600 hover:text-blue-800">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto py-8">
        <div class="px-6">
            <h2 class="text-3xl font-semibold text-gray-700 mb-6">Edit Product Details</h2>
            <!-- Edit Product Form -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $id); ?>" method="POST" class="max-w-xl mx-auto bg-white p-6 rounded-md shadow-md">
                <div class="mb-4">
                    <label for="nama_barang" class="block text-gray-700 font-medium mb-2">Product Name</label>
                    <input type="text" name="nama_barang" id="nama_barang" value="<?php echo htmlspecialchars($product['nama_barang']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="harga_barang" class="block text-gray-700 font-medium mb-2">Price</label>
                    <input type="text" name="harga_barang" id="harga_barang" value="<?php echo htmlspecialchars($product['harga_barang']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="nomor_telepon" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                    <input type="text" name="nomor_telepon" id="nomor_telepon" value="<?php echo htmlspecialchars($product['nomor_telepon']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="kondisi" class="block text-gray-700 font-medium mb-2">Condition</label>
                    <input type="text" name="kondisi" id="kondisi" value="<?php echo htmlspecialchars($product['kondisi']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="deskripsi_barang" class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea name="deskripsi_barang" id="deskripsi_barang" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required><?php echo htmlspecialchars($product['deskripsi_barang']); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="link_maps" class="block text-gray-700 font-medium mb-2">Location Link</label>
                    <input type="text" name="link_maps" id= "link_maps" value="<?php echo htmlspecialchars($product['link_maps']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="lokasi_barang" class="block text-gray-700 font-medium mb-2">Location</label>
                    <input type="text" name="lokasi_barang" id="lokasi_barang" value="<?php echo htmlspecialchars($product['lokasi_barang']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="flex justify-between">
                    <a href="myproduct.php" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">Update Product</button>
                </div>
            </form>
        </div>
    </main>

</body>

</html>
