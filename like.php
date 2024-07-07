<?php
require_once 'koneksi.php'; // Mengimpor file koneksi.php yang berisi informasi tentang koneksi ke database

// Memulai sesi jika belum dimulai
session_start();

// Periksa apakah request merupakan POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Periksa apakah product_id telah diberikan sebagai parameter POST
    if (!isset($_POST['product_id'])) {
        echo "Product ID not provided.";
        exit;
    }

    // Ambil product_id dari parameter POST
    $product_id = intval($_POST['product_id']);

    // Periksa apakah user sudah memberikan like pada produk tersebut sebelumnya
    // Anda perlu mengganti $_SESSION['username'] dengan cara Anda untuk mendapatkan username
    $username = $_SESSION['username'];

    // Periksa apakah user sudah memberikan like pada produk tersebut sebelumnya
    $check_like_query = "SELECT * FROM user_likes WHERE username = ? AND product_id = ?";
    $check_like_stmt = $connection->prepare($check_like_query);

    if (!$check_like_stmt) {
        echo "Failed to prepare query: " . $connection->error;
        exit;
    }

    $check_like_stmt->bind_param('si', $username, $product_id);
    $check_like_stmt->execute();
    $check_like_result = $check_like_stmt->get_result();

    // Jika user belum memberikan like sebelumnya, tambahkan like baru
    if ($check_like_result->num_rows == 0) {
        $insert_like_query = "INSERT INTO user_likes (username, product_id, like_time) VALUES (?, ?, NOW())";
        $insert_like_stmt = $connection->prepare($insert_like_query);

        if (!$insert_like_stmt) {
            echo "Failed to prepare insert query: " . $connection->error;
            exit;
        }

        $insert_like_stmt->bind_param('si', $username, $product_id);

        if ($insert_like_stmt->execute()) {
            // Update jumlah like di tabel detail_barang
            $update_like_query = "UPDATE detail_barang SET jumlah_like = jumlah_like + 1 WHERE id = ?";
            $update_like_stmt = $connection->prepare($update_like_query);

            if (!$update_like_stmt) {
                echo "Failed to prepare update like query: " . $connection->error;
                exit;
            }

            $update_like_stmt->bind_param('i', $product_id);

            if ($update_like_stmt->execute()) {
                // Insert a notification into the database
                $insert_notification_query = "INSERT INTO notifications (username, product_id, action) VALUES (?, ?, 'like')";
                $insert_notification_stmt = $connection->prepare($insert_notification_query);

                if (!$insert_notification_stmt) {
                    echo "Failed to prepare notification insert query: " . $connection->error;
                    exit;
                }

                $insert_notification_stmt->bind_param('si', $username, $product_id);

                if ($insert_notification_stmt->execute()) {
                    // Redirect kembali ke halaman detail produk setelah sukses mengupdate jumlah like
                    header("Location: tampil_detail_product.php?id=" . $product_id);
                    exit;
                } else {
                    echo "Failed to execute notification insert query: " . $insert_notification_stmt->error;
                }
            } else {
                echo "Failed to execute update like query: " . $update_like_stmt->error;
            }
        } else {
            echo "Failed to execute insert query: " . $insert_like_stmt->error;
        }
    } else {
        // Jika user sudah memberikan like sebelumnya, kurangi jumlah like dan hapus entri like dari database
        $delete_like_query = "DELETE FROM user_likes WHERE username = ? AND product_id = ?";
        $delete_like_stmt = $connection->prepare($delete_like_query);

        if (!$delete_like_stmt) {
            echo "Failed to prepare delete query: " . $connection->error;
            exit;
        }

        $delete_like_stmt->bind_param('si', $username, $product_id);

        if ($delete_like_stmt->execute()) {
            // Update jumlah like di tabel detail_barang
            $update_unlike_query = "UPDATE detail_barang SET jumlah_like = jumlah_like - 1 WHERE id = ?";
            $update_unlike_stmt = $connection->prepare($update_unlike_query);

            if (!$update_unlike_stmt) {
                echo "Failed to prepare update unlike query: " . $connection->error;
                exit;
            }

            $update_unlike_stmt->bind_param('i', $product_id);

            if ($update_unlike_stmt->execute()) {
                // Hapus notifikasi jika ada
                $delete_notification_query = "DELETE FROM notifications WHERE username = ? AND product_id = ? AND action = 'like'";
                $delete_notification_stmt = $connection->prepare($delete_notification_query);

                if (!$delete_notification_stmt) {
                    echo "Failed to prepare notification delete query: " . $connection->error;
                    exit;
                }

                $delete_notification_stmt->bind_param('si', $username, $product_id);

                if ($delete_notification_stmt->execute()) {
                    // Redirect kembali ke halaman detail produk setelah sukses mengupdate jumlah like
                    header("Location: tampil_detail_product.php?id=" . $product_id);
                    exit;
                } else {
                    echo "Failed to execute notification delete query: " . $delete_notification_stmt->error;
                }
            } else {
                echo "Failed to execute update unlike query: " . $update_unlike_stmt->error;
            }
        } else {
            echo "Failed to execute delete query: " . $delete_like_stmt->error;
        }
    }
} else {
    echo "Invalid request method";
}
?>
