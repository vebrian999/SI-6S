<?php
session_start();
require_once 'koneksi.php';

// Periksa apakah pengguna telah login atau belum
if (!isset($_SESSION['username'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit;
}

// Ambil informasi pengguna dari database berdasarkan username
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $connection->query($sql);

if ($result->num_rows == 1) {
    // Jika pengguna ditemukan, ambil informasi dan tampilkan di halaman profil
    $row = $result->fetch_assoc();
    $phone_number = $row['phone_number'];
    $address = $row['address'];
    $profile_picture = $row['profile_picture'];
} else {
    // Jika pengguna tidak ditemukan, berikan pesan error
    $error_message = "Informasi pengguna tidak ditemukan.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="./output.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/app.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/your-embed-code.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body class="bg-gray-100">
    <div>
        <?php
        require_once 'header.php';
        ?>
    </div>
    <div class="container mx-auto md:mt-32 mt-28">
        <article class="mt-32 mx-5">
            <div class="bg-white shadow-lg md:px-10 px-5 py-2 rounded-lg">
                <div class="font-medium md:text-5xl text-3xl text-sky-400 text-left flex gap-x-2">
                    <h1>Your Profile</h1>
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="md:flex justify-between font-reguler text-base">
                    <div class="flex gap-x-3 my-10">
                        <div>
                            <img class="w-20 h-20 rounded-full" src="<?php echo $profile_picture; ?>" alt="Rounded avatar" />
                        </div>
                        <div class="flex flex-col justify-center">
                            <h1><?php echo $username; ?></h1>
                            <p><?php echo $phone_number; ?></p>
                        </div>
                    </div>
                    <div class="flex gap-x-4 items-center">
                        <form id="deleteProfilePhotoForm" action="delete_profile_picture.php" method="post">
                            <button type="button" id="deletePhotoButton" class="py-4 md:px-10 px-2.5 bg-red-500  md:text-base text-sm text-white rounded-lg transition duration-300 ease-in-out hover:bg-red-600">Delete Photo</button>
                        </form>
                        <form action="upload_profile_picture.php" method="post" enctype="multipart/form-data">
                            <input type="file" id="profile_picture" name="profile_picture" class="hidden" onchange="this.form.submit()">
                            <label for="profile_picture" class="py-4 md:px-10 px-2.5 bg-sky-400 text-white md:text-base text-sm rounded-lg transition duration-300 ease-in-out hover:bg-sky-500 cursor-pointer">Upload New Photo</label>
                        </form>

                    </div>
                </div>
                <?php if (isset($error_message)) { ?>
                    <p class="text-red-500"><?php echo $error_message; ?></p>
                <?php } ?>

                <form action="update_profile.php" method="post" id="formEditProfile" class="mt-5">
                    <div class="mb-5">
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                        <input type="text" id="username" name="username" class="px-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-5" value="<?php echo $username; ?>" readonly>
                    </div>
                    <div class="mb-5">
                        <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900">Nomor Telepon</label>
                        <input type="text" id="phone_number" name="phone_number" class="px-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-5" value="<?php echo $phone_number; ?>" required>
                    </div>
                    <div class="mb-5">
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Alamat</label>
                        <input type="text" id="address" name="address" class="px-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-5" value="<?php echo $address; ?>" required>
                    </div>
                    <div class="mb-5">
                        <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900">Old Password</label>
                        <input type="password" id="current_password" name="current_password" class="px-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-5" autocomplete="off" placeholder="Password Lama Anda">
                    </div>
                    <div class="mb-5">
                        <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-5" placeholder="Password Baru Anda">
                    </div>
                    <div class="mb-5">
                        <label for="confirm_new_password" class="block mb-2 text-sm font-medium text-gray-900">Confirm New Password</label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-5" placeholder="Konfirmasi Password Baru Anda">
                    </div>
                        <div class="flex gap-x-4 justify-end my-10 mb-48">
                            <a class="border-2 py-4 md:px-10 px-2.5 bg-red-500 text-white text-sm md:text-base rounded-lg transition duration-300 ease-in-out hover:bg-red-600" href="dashboard.php">Cancel</a>
                            <button type="submit" name="submit" class="py-4 md:px-10 px-2.5 md:text-base text-white bg-green-400 rounded-lg transition duration-300 ease-in-out hover:bg-green-600 hover:text-white" id="saveChangesButton">Save Changes</button>
                        </div>
                </form>
            </div>
        </article>
    </div>
</body>

    <!-- cdn flowbite -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deletePhotoButton = document.getElementById('deletePhotoButton');
        var deleteProfilePhotoForm = document.getElementById('deleteProfilePhotoForm');

        deletePhotoButton.addEventListener('click', function () {
            if (confirm('Are you sure you want to delete your profile photo?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', deleteProfilePhotoForm.action, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert('Profile photo deleted successfully.');
                            location.reload();
                        } else {
                            alert('An error occurred while deleting the profile photo.');
                        }
                    }
                };
                xhr.send();
            }
        });

        var form = document.getElementById('formEditProfile');

        form.addEventListener('submit', function (event) {
            var phoneNumberInput = document.getElementById('phone_number');
            var addressInput = document.getElementById('address');
            var newPasswordInput = document.getElementById('new_password');
            var confirmNewPasswordInput = document.getElementById('confirm_new_password');

            var phoneNumberRegex = /^[0-9]{10,12}$/;
            if (!phoneNumberRegex.test(phoneNumberInput.value)) {
                event.preventDefault();
                alert('Masukkan nomor telepon yang valid.');
                return;
            }

            if (addressInput.value.trim() === '') {
                event.preventDefault();
                alert('Alamat tidak boleh kosong.');
                return;
            }

            if (newPasswordInput.value !== '' || confirmNewPasswordInput.value !== '') {
                if (newPasswordInput.value !== confirmNewPasswordInput.value) {
                    event.preventDefault();
                    alert('Konfirmasi kata sandi baru tidak cocok.');
                    return;
                }
            }
        });
    });
</script>

