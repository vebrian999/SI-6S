<!-- halaman tampil_notifikasi.php -->

<?php
// Koneksi ke database
require_once 'koneksi.php';

// Periksa jika sesi belum dimulai sebelumnya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil username pengguna saat ini dari sesi
if (!isset($_SESSION['username'])) {
    echo "Tidak ada pengguna yang terautentikasi.";
    exit;
}
$username = $_SESSION['username'];

// Query untuk mengambil notifikasi dari database
$query = "SELECT n.id AS notification_id, n.created_at AS notification_time, 
                 db.nama_barang, n.username, u.profile_picture, n.product_id, 
                 c.comment, c.id AS comment_id, c.username AS commenter_username, n.action
          FROM notifications n
          LEFT JOIN detail_barang db ON n.product_id = db.id 
          LEFT JOIN users u ON n.username = u.username
          LEFT JOIN comments c ON n.parent_id = c.id
          WHERE n.username != ? AND db.username = ? AND (n.action = 'like' OR n.action = 'comment')";
$stmt = $connection->prepare($query);
$stmt->bind_param('ss', $username, $username);
$stmt->execute();
$result = $stmt->get_result();

// Menghasilkan notifikasi ke dalam array
$notifications = [];
while ($row = $result->fetch_assoc()) {
    // Tentukan foto profil default
    $default_profile_picture = 'assets/img/new/Pro max pfp.jpg';

    // Tentukan foto profil yang akan digunakan
    $profile_picture = isset($row['profile_picture']) ? $row['profile_picture'] : $default_profile_picture;

    // Tentukan jenis notifikasi berdasarkan tindakan
    if ($row['action'] === 'like') {
        $notification_type = 'menyukai';
    } elseif ($row['action'] === 'comment' && $row['commenter_username'] !== $username) {
        $notification_type = 'mengomentari';
    } else {
        continue; // Skip jika bukan notifikasi yang relevan untuk ditampilkan
    }

    // Pesan notifikasi
    $message = $row['username'] . ' ' . $notification_type . ' barang Anda: ' . $row['nama_barang'];

    // Jika ini notifikasi komentar, tambahkan pesan komentar
    if ($row['action'] === 'comment') {
        $message .= ' - ' . $row['comment'];
    }

    $notifications[] = [
        'id' => $row['notification_id'], // Tambahkan ID notifikasi
        'time' => $row['notification_time'],
        'message' => $message,
        'profile_picture' => $profile_picture,
        'product_id' => $row['product_id'], // Tambahkan product_id ke dalam array notifikasi
        'comment' => $row['comment'],
        'comment_id' => $row['comment_id'],
        'commenter_username' => $row['commenter_username']
    ];
}

// Tutup koneksi database
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    <!-- Masukkan CSS yang diperlukan di sini -->
    <link rel="stylesheet" href="output.css">
    <link rel="stylesheet" href="assets/app.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/your-embed-code.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <!-- header -->
    <div>
        <?php require_once 'header.php'; ?>
    </div>
    <!-- Konten notifikasi -->
    <main>
        <div id="content" class="content">
            <article class="md:my-32 my-24 md:mx-32">
                <div class="bg-white shadow-lg md:px-10 px-3 py-2 rounded-lg">
                    <div class="font-medium md:text-5xl text-3xl text-sky-400 text-left flex gap-x-2">
                        <h1>Notifikasi</h1>
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <div>
                            <button type="button"
                                class="text-black hover:bg-sky-400 md:text-base hover:text-white focus:bg-opacity-70 focus:text-blue-600 focus:bg-sky-200 font-medium rounded-lg text-sm px-3 py-1 my-4 selected">Semua</button>
                            <button type="button"
                                class="text-black hover:bg-sky-400 md:text-base hover:text-white focus:bg-opacity-70 focus:text-blue-600 focus:bg-sky-200 font-medium rounded-lg text-sm px-3 py-1 my-4">Belum Dibaca</button>
                        </div>
                    </div>
                    <h1 class="text-left font-normal my-4">Baru</h1>
                    <div>
                        <!-- Container untuk notifikasi -->
                        <?php if (!empty($notifications)) : ?>
                        <?php foreach ($notifications as $index => $notification) : ?>
                        <a href="tampil_detail_product.php?id=<?php echo $notification['product_id']; ?>"
                            class="notification-link">
                            <div id="alert-<?php echo $notification['id']; ?>" class="notification-item flex items-center p-4 mb-4 text-blue-800 rounded-lg bg-blue-50 cursor-pointer relative transition-colors duration-300 ease-in-out hover:bg-blue-100">
                                <img class="flex-shrink-0 w-8 h-8 rounded-full"
                                    src="<?php echo $notification['profile_picture']; ?>" alt="Avatar" />
                                <div class="ms-3">
                                    <p class="text-base font-normal text-black"><?php echo $notification['message']; ?></p>
                                    <p class="text-sm font-normal text-blue-500"><?php echo date('Y-m-d H:i:s', strtotime($notification['time'])); ?></p>
                                    <?php if (isset($notification['comment'])) : ?>
                                    <p class="text-sm font-normal text-gray-600"><?php echo $notification['comment']; ?></p>
                                    <?php endif ?>
                                </div>
                                <span class="absolute top-50 right-3 h-3 w-3 bg-blue-600 rounded-full"></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                        <?php else : ?>
                        <p>Tidak ada notifikasi baru.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        </div>
    </main>
    <!-- Masukkan JavaScript yang diperlukan di sini -->
    <script>
        const notificationItems = document.querySelectorAll(".notification-item");

        notificationItems.forEach((item) => {
            item.addEventListener("click", () => {
                // Periksa apakah pesan sudah dibaca sebelumnya
                const isRead = item.getAttribute("data-read") === "true";

                // Jika pesan belum dibaca, tandai sebagai sudah dibaca dan ubah tampilan
                if (!isRead) {
                    item.setAttribute("data-read", "true");

                    // Hilangkan ikon titik
                    const dotIcon = item.querySelector(".bg-blue-600");

                    if (dotIcon) {
                        dotIcon.style.display = "none";
                    }

                    // Ubah background
                    item.classList.remove("bg-blue-50");
                    item.classList.add("bg-white");
                }

                // Ambil product_id dari ID notifikasi
                const notificationId = item.id.split("-")[1];
                const productId = getProductId(notificationId);

                // Redirect ke halaman detail produk
                window.location.href = "tampil_detail_product.php?id=" + productId;
            });
        });

        // Fungsi untuk mendapatkan product_id dari ID notifikasi
        function getProductId(notificationId) {
            // Loop melalui array notifikasi untuk mencari product_id berdasarkan ID notifikasi
            for (const notification of notifications) {
                if (notification.id === notificationId) {
                    return notification.product_id;
                }
            }
            return null; // Mengembalikan null jika product_id tidak ditemukan
        }
    </script>
</body>
</html>