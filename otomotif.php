<?php
 session_start();
require_once 'koneksi.php'; // Mengimpor file koneksi.php yang berisi informasi tentang koneksi ke database
// Periksa apakah pengguna telah login atau belum
if (!isset($_SESSION['username'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit;
}

// Periksa apakah koneksi berhasil
if ($connection->connect_error) {
    die("Koneksi database gagal: " . $connection->connect_error);
}

// Query SQL untuk mengambil informasi username, nama barang, harga barang, dan gambar barang dari tabel detail_barang
$query = "SELECT id, username, nama_barang, harga_barang, gambar_barang_1 FROM detail_barang WHERE kategori = 'otomotif'";

// Jalankan query
$result = $connection->query($query);

// Periksa apakah query berhasil dieksekusi
if ($result->num_rows > 0) {
    // Fetch all rows into an array
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>6SECOND</title>
  <link rel="stylesheet" href="assets/app.css" />
  <link href="./output.css" rel="stylesheet">
  <!-- Menambahkan link untuk menggunakan Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <!-- Menambahkan link untuk menggunakan Font Awesome -->
  <script src="https://use.fontawesome.com/your-embed-code.js"></script>
  <!-- Menambahkan link untuk menggunakan Flowbite CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
  <style>
    .card-image {
      width: 320px;
      height: 275px;
    }
  </style>
</head>

<header>
            <?php require_once 'header.php'; ?>
</header>
<body>


    <!-- start card -->
<div class="mb-44">



<!-- bagian di bawah ini nanti di hapus ya mx-16 nya ke dauble soal nya -->
    <div class="md:grid md:grid-cols-5 my-10 justify-center gap-x-10 md:mx-16 mx-7"> 
        <div class="md:mx-10 mx-3 col-span-5">
            <h1 class="text-blue font-medium md:text-4xl text-3xl -ml-3 md:mt-20 mt-24">Kategori Otomotif</h1>
        </div>
        <?php
        // Loop through the array to display cards
        foreach ($rows as $row) {
            // Membatasi nama barang menjadi maksimal 3 kata
            $nama_barang = $row['nama_barang'];
            $words = explode(" ", $nama_barang);
            $short_name = implode(" ", array_slice($words, 0, 3));
            ?>
            <div class="group flex my-7 justify-center md:col-span-1 max-w-screen-xl md:max-w-xs flex-col overflow-hidden bg-card-ec hover:shadow-xl">
                <a href="tampil_detail_product.php?id=<?php echo $row['id']; ?>" class="relative flex h-72 overflow-hidden justify-center">
                    <img class="card-image md:absolute w-full top-0 right-0 object-cover" src="<?php echo $row['gambar_barang_1']; ?>" alt="product image" />
                    <div class="absolute bottom-0 mb-6 flex w-full justify-center space-x-4">
                        <div class="h-3 w-3 rounded-full border-2 border-white bg-white"></div>
                        <div class="h-3 w-3 rounded-full border-2 border-white bg-transparent"></div>
                        <div class="h-3 w-3 rounded-full border-2 border-white bg-transparent"></div>
                    </div>
<div class="absolute -right-16 bottom-10 mr-2 mb-0 space-y-2 transition-all duration-300 group-hover:right-0">
    <form action="like.php" method="post" title="Jumlah Like">
        <!-- Tambahkan input tersembunyi untuk menyimpan product_id -->
        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <?php
        // Periksa apakah pengguna sudah memberikan like pada produk
        $like_class = '';
        $like_text_color = 'text-gray-700'; // Warna teks default
        // Anda perlu menyesuaikan logika ini dengan cara Anda untuk menentukan apakah pengguna sudah memberikan like
        // Misalnya, Anda dapat menggunakan query database seperti yang dilakukan di like.php
        $query = "SELECT * FROM user_likes WHERE username = ? AND product_id = ?";
        $stmt = $connection->prepare($query);
        if ($stmt) {
            $stmt->bind_param('si', $_SESSION['username'], $row['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $like_class = 'text-red-500'; // Kelas untuk mengubah warna ikon menjadi merah
                // $like_text_color = 'text-red-500';
                // Warna teks menjadi merah jika sudah dilike
            }
        }
        ?>
        <button class="flex h-10 w-10 items-center justify-center bg-gray-900 text-white transition hover:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 <?php echo $like_class; ?>" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
            </svg>
        </button>
    </form>
</div>


</a>
<div class="mt-4 pb-5 flex flex-col">
<a href="tampil_detail_product.php?id=<?php echo $row['id']; ?>">
<h1 class="text-center tracking-tight font-medium text-xl"><?php echo $short_name; ?></h1>
<h2 class="text-center tracking-tight font-light text-gray-500">Upload by <?php echo $row['username']; ?></h2>
</a>
<div class="flex justify-center">
<p><span class="text-base text-blue font-medium">Rp <?php echo number_format($row['harga_barang'], 0, '', '.'); ?></span></p>
</div>
</div>
</div>
<?php
     }
     ?>
</div>

</div>
<!-- end card -->
<?php
} else {
    // Jika query gagal dieksekusi, tampilkan pesan error
    echo "Tidak ada data yang ditemukan.";
}
// Tutup koneksi database
// $connection->close();
?>

<!-- Konten Anda di sini -->
<!-- Menambahkan script untuk menggunakan Flowbite JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

<header>
            <?php require_once 'footer.php'; ?>
</header>
</html>