<?php
session_start();
require_once 'koneksi.php';

// Periksa apakah pengguna telah login atau belum
if (!isset($_SESSION['username'])) {
    // Jika belum login, kirim respons JSON ke klien dengan pesan error
    echo json_encode(array("success" => false, "message" => "Anda harus login untuk menyukai produk."));
    exit;
}

// Periksa apakah permintaan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah id produk diterima dari permintaan POST
    if (isset($_POST["id"])) {
        $productId = $_POST["id"];
        
        // Perbarui jumlah like di database
        $sql = "UPDATE detail_barang SET jumlah_like = jumlah_like + 1 WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $productId);
        
        if ($stmt->execute()) {
            // Jika perbaruan berhasil, kirim respons JSON ke klien
            echo json_encode(array("success" => true));
        } else {
            // Jika perbaruan gagal, kirim respons JSON ke klien dengan pesan error
            echo json_encode(array("success" => false, "message" => "Gagal memperbarui jumlah like."));
        }
    } else {
        // Jika id produk tidak diterima dari permintaan POST, kirim respons JSON ke klien dengan pesan error
        echo json_encode(array("success" => false, "message" => "Id produk tidak ditemukan dalam permintaan."));
    }
} else {
    // Jika permintaan bukan POST, kirim respons JSON ke klien dengan pesan error
    echo json_encode(array("success" => false, "message" => "Metode permintaan tidak valid."));
}

// Tutup koneksi ke database
$connection->close();
?>
