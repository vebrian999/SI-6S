<?php
require_once 'koneksi.php'; // Koneksi ke database

// Inisialisasi variabel untuk menyimpan pesan error
$username_err = $password_err = $phone_number_err = $address_err = $role_err = $profile_picture_err = "";

// Proses data yang dikirim dari formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Username tidak boleh kosong.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validasi input password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Password tidak boleh kosong.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validasi input nomor telepon
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Nomor telepon tidak boleh kosong.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    // Validasi input alamat
    if (empty(trim($_POST["address"]))) {
        $address_err = "Alamat tidak boleh kosong.";
    } else {
        $address = trim($_POST["address"]);
    }

    // Validasi input peran (role)
    if (empty(trim($_POST["role"]))) {
        $role_err = "Peran tidak boleh kosong.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Validasi input foto profil
    if (empty($_FILES["profile_picture"]["name"])) {
        $profile_picture_err = "Foto profil tidak boleh kosong.";
    } else {
        // Lakukan validasi dan pemrosesan upload gambar di sini
    }

    // Jika tidak ada pesan error, masukkan data ke database
    if (empty($username_err) && empty($password_err) && empty($phone_number_err) && empty($address_err) && empty($role_err) && empty($profile_picture_err)) {
        // Query untuk menyimpan data ke database
        $sql = "INSERT INTO users (username, password, phone_number, address, role, profile_picture) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $connection->prepare($sql)) {
            // Bind parameter ke statement
            $stmt->bind_param("ssssss", $param_username, $param_password, $param_phone_number, $param_address, $param_role, $param_profile_picture);

            // Set parameter
            $param_username = $username;
            $param_password = $password; // Jangan lupa untuk mengubah ini sesuai kebutuhan Anda, misalnya, Anda dapat menggunakan password_hash()
            $param_phone_number = $phone_number;
            $param_address = $address;
            $param_role = $role;
            $param_profile_picture = $profile_picture;

            // Eksekusi statement
            if ($stmt->execute()) {
                // Redirect kembali ke halaman utama setelah berhasil menambahkan pengguna
                header("Location: index.php");
                exit;
            } else {
                echo "Terjadi kesalahan. Silakan coba lagi.";
            }

            // Tutup statement
            $stmt->close();
        }
    }

    // Tutup koneksi database
    $connection->close();
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
        <div class="p-4 sm:ml-64 md:mt-20">
            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg mt-12">
                <h2 class="text-2xl mb-4 font-bold">Tambah Pengguna</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username:</label>
                    <input type="text" name="username" id="username" class="border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md" required>
                    <span class="text-sm text-red-600"><?php echo $username_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                    <input type="password" name="password" id="password" class="border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md" required>
                    <span class="text-sm text-red-600"><?php echo $password_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon:</label>
                    <input type="text" name="phone_number" id="phone_number" class="border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md" required>
                    <span class="text-sm text-red-600"><?php echo $phone_number_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat:</label>
                    <input type="text" name="address" id="address" class="border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md" required>
                    <span class="text-sm text-red-600"><?php echo $address_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Peran:</label>
                    <input type="text" name="role" id="role" class="border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md" required>
                    <span class="text-sm text-red-600"><?php echo $role_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="profile_picture" class="block text-sm font-medium text-gray-700">Foto Profil:</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md" required>
                    <span class="text-sm text-red-600"><?php echo $profile_picture_err; ?></span>
                </div>
                <div class="mb-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Tambah Pengguna</button>
                </div>
            </form>

            </div>
        </div>
    </div>
</main>

<aside>
    <?php require_once 'admin_aside_menu.php'; ?>
</aside>
</body>
</html>