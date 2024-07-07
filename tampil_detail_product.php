<?php
session_start();
require_once 'koneksi.php'; // Mengimpor file koneksi.php yang berisi informasi tentang koneksi ke database

// Periksa apakah ID produk telah diberikan sebagai parameter GET
if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan.";
    exit;
}

// Ambil ID produk dari parameter GET
$product_id = intval($_GET['id']);

// Perbarui jumlah tampilan produk di database
$update_views_query = "UPDATE detail_barang SET jumlah_views = jumlah_views + 1 WHERE id = ?";
$update_views_stmt = $connection->prepare($update_views_query);
$update_views_stmt->bind_param('i', $product_id);
$update_views_stmt->execute();

// Query SQL untuk mengambil detail produk berdasarkan ID
$query = "SELECT id, username, nama_barang, harga_barang, nomor_telepon, kondisi, deskripsi_barang, link_maps, gambar_barang_1, gambar_barang_2, gambar_barang_3, gambar_barang_4, tanggal_masuk, jumlah_like, jumlah_views, lokasi_barang FROM detail_barang WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah produk ditemukan
if ($result->num_rows == 0) {
    echo "Produk tidak ditemukan.";
    exit;
}

// Ambil detail produk
$product = $result->fetch_assoc();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>6SECOND</title>
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

<body>

    <!-- header -->
    <header>
        <?php require_once 'header.php'; ?>
    </header>

    <!-- awal main -->
    <main>
        <div id="content" class="content">
            <!-- awal detail produk -->
            <section class="py-6 mt-24 bg-white md:py-16 antialiased">
                <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
                    <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
                        <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                            <img id="mainImage" class="w-[700px] h-[450px] object-cover" src="<?php echo htmlspecialchars($product['gambar_barang_1']); ?>" alt="" />

                            <div class="grid grid-cols-4 gap-4 mt-4">
                                <a href="#" onclick="changeMainImage(event, '<?php echo htmlspecialchars($product['gambar_barang_1']); ?>')">
                                    <img class="w-full h-auto object-cover max-w-[96px] max-h-[86px]" src="<?php echo htmlspecialchars($product['gambar_barang_1']); ?>" alt="" />
                                </a>
                                <a href="#" onclick="changeMainImage(event, '<?php echo htmlspecialchars($product['gambar_barang_2']); ?>')">
                                    <img class="w-full h-auto object-cover max-w-[96px] max-h-[86px]" src="<?php echo htmlspecialchars($product['gambar_barang_2']); ?>" alt="" />
                                </a>
                                <a href="#" onclick="changeMainImage(event, '<?php echo htmlspecialchars($product['gambar_barang_3']); ?>')">
                                    <img class="w-full h-auto object-cover max-w-[96px] max-h-[86px]" src="<?php echo htmlspecialchars($product['gambar_barang_3']); ?>" alt="" />
                                </a>
                                <a href="#" onclick="changeMainImage(event, '<?php echo htmlspecialchars($product['gambar_barang_4']); ?>')">
                                    <img class="w-full h-auto object-cover max-w-[96px] max-h-[86px]" src="<?php echo htmlspecialchars($product['gambar_barang_4']); ?>" alt="" />
                                </a>
                            </div>
                        </div>

                        <div class="mt-6 sm:mt-8 lg:mt-0">
                            <h1 class="text-xl font-medium text-gray-900 sm:text-2xl"><?php echo htmlspecialchars($product['nama_barang']); ?></h1>
                            <div class="sm:items-center sm:gap-4">
                                <div class="flex items-center gap-2 my-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                            </svg>
                                            <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                            </svg>
                                            <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                            </svg>
                                            <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                            </svg>
                                            <svg class="w-4 h-4 ms-1 text-gray-300 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium leading-none text-gray-500">(4.0)</p>
                                    <a href="#" class="text-sm font-medium leading-none text-gray-900 underline hover:no-underline">345 Reviews</a>
                                </div>
                                <!-- ini buat default dulu -->
                                <!-- Ini adalah bagian yang menampilkan jumlah review dan bintang rating -->
                                <!-- Di sini Anda dapat menampilkan jumlah review dan bintang rating berdasarkan data yang ada di database -->

                            </div>
                            <!-- Ini adalah bagian yang menampilkan harga -->
                            <p class="text-3xl font-semibold text-gray-900">Rp <?php echo number_format($product['harga_barang'], 0, ',', '.'); ?></p>
                            <!-- Ini adalah bagian yang menampilkan informasi ketersediaan produk, tanggal masuk, dan kondisi -->
                            <div class="my-10 flex flex-col gap-y-2">
                                <p class="text-gray-400 font-medium text-base">Availability : <span class="text-sky-500">In Stock</span></p>
                                <p class="text-gray-400 font-medium text-base">Posted : <span class="text-sky-500"><?php echo htmlspecialchars($product['tanggal_masuk']); ?></span></p>
                                <p class="text-gray-400 font-medium text-base">Kondisi : <span class="text-sky-500"><?php echo htmlspecialchars($product['kondisi']); ?></span></p>
                            </div>

                            <!-- Ini adalah bagian yang menampilkan tombol untuk melakukan pembelian atau penawaran -->
                            <div>
                                <hr class="border-t border-gray-300 font-semibold w-full my-4" />
                                <div class="mt-4 sm:my-4 gap-x-8 items-center gap-y-3 md:gap-y-0  flex justify-center flex-wrap">
                                    <a href="#" class="py-4 px-16 text-sm font-normal text-black bg-white rounded-md border-2 border-sky-400 hover:bg-sky-400 hover:text-white border-t border-b sm:mb-0 " role="button">
                                        Rp <?php echo htmlspecialchars(number_format($product['harga_barang'], 0, ',', '.')); ?>
                                    </a>
                                    <a href="https://api.whatsapp.com/send?phone=<?php echo htmlspecialchars($product['nomor_telepon']); ?>" class="py-4 px-16 text-white bg-sky-400 hover:bg-sky-500 font-normal rounded-md text-sm border-gray-300 border-b" role="button">Buat Tawaran</a>
                                </div>
                                <hr class="border-t border-gray-300 font-semibold w-full my-4" />
                            </div>

                            <!-- Ini adalah bagian yang menampilkan tombol untuk menghubungi penjual, jumlah like, lokasi, dan jumlah views -->
                            <div class="flex items-center md:gap-x-4 gap-x-2 my-10">
                                <a href="https://api.whatsapp.com/send?phone=<?php echo htmlspecialchars($product['nomor_telepon']); ?>" class="rounded-lg border bg-sky-400 hover:bg-sky-500 md:text-base text-xs text-center text-white py-3 md:py-10 md:px-6 px-1.5">Hubungi Sekarang</a>


                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


                                <!-- likes -->
                                <form action="like.php" method="post" title="Jumlah Like">
                                    <!-- Tambahkan input tersembunyi untuk menyimpan product_id -->
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">

                                    <?php
                                    // Periksa apakah pengguna sudah memberikan like pada produk
                                    $like_class = '';
                                    $like_text_color = 'text-gray-700'; // Warna teks default
                                    // Anda perlu menyesuaikan logika ini dengan cara Anda untuk menentukan apakah pengguna sudah memberikan like
                                    // Misalnya, Anda dapat menggunakan query database seperti yang dilakukan di like.php
                                    $query = "SELECT * FROM user_likes WHERE username = ? AND product_id = ?";
                                    $stmt = $connection->prepare($query);
                                    if ($stmt) {
                                        $stmt->bind_param('si', $_SESSION['username'], $product['id']);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            $like_class = 'text-red-500'; // Kelas untuk mengubah warna ikon menjadi merah
                                            // $like_text_color = 'text-red-500';
                                            // Warna teks menjadi merah jika sudah dilike
                                        }
                                    }
                                    ?>

                                    <button type="submit" id="likeButton" class="md:font-medium py-2 flex md:px-4 px-2.5 rounded-full border  border-gray-400 hover:border-sky-400 hover:bg-sky-400 <?php echo $like_class; ?>">
                                        <svg class="w-4 h-4 inline-block mr-1 mt-1 <?php echo $like_class; ?>" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 18l-1.45-1.316C4.357 13.56 2 11.367 2 8.5 2 6.019 4.019 4 6.5 4c1.543 0 2.914.793 3.5 2  .586-1.207 1.957-2 3.5-2 2.481 0 4.5 2.019 4.5 4.5 0 2.867-2.357 5.06-6.55 8.184L10 18z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span id="likeText" class="<?php echo $like_text_color; ?>"><?php echo htmlspecialchars($product['jumlah_like']); ?></span>
                                    </button>
                                </form>

                                <!-- lokasi_barang -->
                                <a href="<?php echo htmlspecialchars($product['link_maps']); ?>" target="_blank" title="Lokasi barang" rel="noopener noreferrer" class="text-gray-700 flex md:text-base text-sm items-center gap-2 rounded-full border border-gray-400 hover:border-sky-400 hover:bg-sky-400 hover:text-white py-2 px-4">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span><?php echo ucwords($product['lokasi_barang']); ?></span>
                                </a>
                                <!-- jumlah_viwers -->

                                <div class="flex items-center md:text-base text-sm gap-2 text-gray-700 rounded-full border border-gray-400 py-2 md:px-4 px-2.5" title="Jumlah Views">
                                    <i class="fa-solid fa-eye text-gray-700"></i>
                                    <span><?php echo htmlspecialchars($product['jumlah_views']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ini adalah bagian yang menampilkan deskripsi produk -->
                    <div class="flex flex-col mx-10 gap-y-10 mt-10">
                        <h1 class="font-semibold md:text-3xl text-left">Deskrisi produk</h1>
                        <div class="mx-3 font-light text-base text-gray-400 text-left">
                            <p class="mb-10"><?php echo htmlspecialchars($product['deskripsi_barang']); ?></p>
                        </div>
                    </div>

                    <!-- Awal komentar produk -->
                    <section class="py-6 bg-white md:py-16 antialiased">
                        <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
                            <h2 class="text-2xl font-medium text-gray-900">Komentar</h2>
                            <div class="mt-4">
                                <form action="submit_comment.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                                    <textarea name="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Tulis komentar Anda di sini..."></textarea>
                                    <!-- Tambahkan input tersembunyi untuk menandai balasan penjual -->
                                    <input type="hidden" name="is_seller_reply" value="1"> <!-- Jika balasan berasal dari penjual, set nilainya menjadi 1 -->
                                    <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white rounded-full hover:bg-green-700">Kirim</button>
                                </form>
                            </div>
                            <div class="mt-6">
                                <?php
                                // Function untuk menampilkan komentar dan balasan
                                function display_comments($comments, $parent_id = 0, $margin_left = 0)
                                {
                                    foreach ($comments as $comment) {
                                        if ($comment['parent_id'] == $parent_id) {
                                            echo '<div class="mb-4 p-4 bg-gray-100 rounded-lg" style="margin-left: ' . $margin_left . 'px;">';
                                            echo '<p class="text-sm text-gray-700"><strong>' . htmlspecialchars($comment['username']) . '</strong>: ' . htmlspecialchars($comment['comment']) . '</p>';
                                            echo '<span class="text-xs text-gray-500">' . htmlspecialchars($comment['created_at']) . '</span>';
                                            // Form untuk membalas komentar
                                            echo '<form action="submit_comment.php" method="post" class="mt-2">';
                                            echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($_GET['id']) . '">';
                                            echo '<input type="hidden" name="parent_id" value="' . htmlspecialchars($comment['id']) . '">';
                                            echo '<textarea name="comment" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Balas komentar ini..."></textarea>';
                                            echo '<button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white rounded-full hover:bg-green-700">Balas</button>';
                                            echo '</form>';
                                            echo '</div>';
                                            // Panggil fungsi ini secara rekursif untuk menampilkan balasan
                                            display_comments($comments, $comment['id'], $margin_left + 20);
                                        }
                                    }
                                }

                                // Query untuk mengambil komentar berdasarkan id produk
                                $query = $connection->prepare("SELECT * FROM comments WHERE product_id = ? ORDER BY created_at DESC");
                                $query->bind_param('i', $product_id);
                                $query->execute();
                                $result = $query->get_result();
                                $comments = $result->fetch_all(MYSQLI_ASSOC);

                                // Tampilkan komentar
                                display_comments($comments);
                                ?>
                            </div>
                        </div>
                    </section>
                    <!-- Akhir komentar produk -->
                </div>
            </section>
            <script>
                function changeMainImage(event, imageUrl) {
                    event.preventDefault();
                    document.getElementById("mainImage").src = imageUrl;
                }
            </script>
            <!-- akhir detail produk -->
        </div>
    </main>
    <!-- akhir  main -->
    <!-- akhir  main -->

    <!-- cdn flowbite -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>