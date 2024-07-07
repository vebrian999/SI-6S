<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>6SECOND - Produk Favorit</title>
  <link rel="stylesheet" href="assets/app.css" />
  <link href="./output.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/your-embed-code.js"></script>
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


<?php
// session_start();
require_once 'koneksi.php'; 

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($connection->connect_error) {
    die("Koneksi database gagal: " . $connection->connect_error);
}

$username = $_SESSION['username'];

// Query untuk mengambil produk yang disukai oleh pengguna
$query = "SELECT db.id, db.username, db.nama_barang, db.harga_barang, db.gambar_barang_1 
          FROM detail_barang db 
          JOIN user_likes ul ON db.id = ul.product_id 
          WHERE ul.username = ?";

$stmt = $connection->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <div class="mb-44 mt-28">
          <div class="flex items-center justify-between md:mx-36 mx-3">

            <div class="font-medium md:text-5xl text-3xl text-sky-400 text-left flex gap-x-2">
              <h1>Your Favorit</h1>
              <i class="fa-solid fa-heart"></i>
            </div>
            <div class="">
              <button
                id="dropdownDefaultButton1"
                data-dropdown-toggle="dropdown1"
                class="text-sky-400 border border-sky-400 hover:bg-sky-400 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center"
                type="button">
                Urutkan
                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                </svg>
              </button>

              <!-- Dropdown menu -->
              <div id="dropdown1" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton1">
                  <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Paling Murah</a>
                  </li>
                  <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Paling Mahal</a>
                  </li>
                  <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Sering Dilihat</a>
                  </li>
                  <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Populer</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        <div class="grid grid-cols-5 justify-center gap-x-10 mx-5 md:mx-32">
            <?php
            foreach ($rows as $row) {
                $nama_barang = $row['nama_barang'];
                $words = explode(" ", $nama_barang);
                $short_name = implode(" ", array_slice($words, 0, 3));
                ?>
                <div class="group flex mt-10 justify-center md:col-span-1 col-span-5 w-full max-w-screen-xl md:max-w-xs flex-col overflow-hidden bg-card-ec hover:shadow-xl">
                    <a href="tampil_detail_product.php?id=<?php echo $row['id']; ?>" class="relative flex h-72 overflow-hidden justify-center">
                        <img class="card-image absolute w-full top-0 right-0 object-cover" src="<?php echo $row['gambar_barang_1']; ?>" alt="product image" />
                        <div class="absolute bottom-0 mb-6 flex w-full justify-center space-x-4">
                            <div class="h-3 w-3 rounded-full border-2 border-white bg-white"></div>
                            <div class="h-3 w-3 rounded-full border-2 border-white bg-transparent"></div>
                            <div class="h-3 w-3 rounded-full border-2 border-white bg-transparent"></div>
                        </div>
                        <div class="absolute -right-16 bottom-10 mr-2 mb-0 space-y-2 transition-all duration-300 group-hover:right-0">
                            <form action="like.php" method="post" title="Jumlah Like">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <?php
                                $like_class = 'text-red-500';
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
    <?php
} else {
    echo "<p class='text-center my-10'>Anda belum menyukai produk apapun.</p>";
}
$connection->close();
?>

    <!-- cdn flowbite -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
