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

// Periksa apakah ada kata kunci pencarian yang dikirim dari formulir
if (isset($_GET['keyword'])) {
    // Tangkap kata kunci dari formulir
    $keyword = $_GET['keyword'];

    // Query SQL untuk mencari produk berdasarkan nama barang yang cocok dengan kata kunci
    $query = "SELECT id, username, nama_barang, harga_barang, gambar_barang_1 FROM detail_barang WHERE nama_barang LIKE '%$keyword%'";

    // Jalankan query
    $result = $connection->query($query);

    // Periksa apakah query berhasil dieksekusi
    if ($result) {
        // Periksa apakah ada hasil yang ditemukan
        if ($result->num_rows > 0) {
            // Fetch all rows into an array
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            ?>

            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>6SECOND - Hasil Pencarian</title>
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
            <body>

                <!-- header -->
    <header>
        <?php require_once 'header.php'; ?>
    </header>
                <!-- start card -->
                <div class="mb-44">
                    <div class="md:grid md:grid-cols-5 my-10 justify-center gap-x-10  mx-7 md:mx-16 "> 
                        <div class="mx-10 col-span-5">
                            <h1 class="text-blue font-medium md:text-4xl text-3xl -ml-3 md:mt-20 mt-24">Hasil Pencarian</h1>
                        </div>
                        <?php foreach ($rows as $row): 
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
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- end card -->

                <!-- Menambahkan script untuk menggunakan Flowbite JS -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
            </body>
            </html>

            <?php
        } else {
            // Jika tidak ada hasil yang ditemukan, tampilkan pesan
            echo "Tidak ada data yang ditemukan.";
        }
    } else {
        // Jika query gagal dieksekusi, tampilkan pesan error
        echo "Query gagal dieksekusi: " . $connection->error;
    }
} else {
    // Jika tidak ada kata kunci pencarian, tampilkan pesan
    echo "Masukkan kata kunci untuk melakukan pencarian.";
}

// Tutup koneksi database
$connection->close();
?>
