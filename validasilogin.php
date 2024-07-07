<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <link href="./output.css" rel="stylesheet">
</head>

<body>
    <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-md p-6 bg-white rounded-md shadow-md" style="margin-top: -100px;">
        <h2 class="text-2xl font-bold mb-4 text-center text-green-600">Anda masuk setelah 6 detik</h2>

            <?php
            session_start();

            // Tampilkan pesan error jika ada
            if (isset($_SESSION['error'])) {
                echo '<p class="text-red-600 font-semibold mb-4">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']); // Hapus pesan error setelah ditampilkan
            }

            // Tampilkan pesan sukses jika ada
            if (isset($_SESSION['success_message'])) {
                echo '<p id="success-message" class="text-green-600 font-semibold mb-4">' . $_SESSION['success_message'] . '</p>';
                unset($_SESSION['success_message']); // Hapus pesan sukses setelah ditampilkan
            }
            ?>

            <p id="redirect-info" class="text-center">Redirecting to login page in <span id="countdown">6</span> seconds...</p>

            <script>
                var count = 6; // Waktu countdown

                // Fungsi untuk mengurangi waktu countdown dan mengarahkan pengguna ke halaman login setelah waktu habis
                function countdown() {
                    var countdownElement = document.getElementById("countdown");
                    var redirectInfoElement = document.getElementById("redirect-info");

                    countdownElement.textContent = count;

                    // Mengurangi waktu countdown
                    count--;

                    // Mengarahkan pengguna ke halaman login setelah waktu habis
                    if (count < 0) {
                        window.location.href = "dashboard.php";
                    } else {
                        // Melanjutkan countdown
                        setTimeout(countdown, 1000);
                    }
                }

                // Memulai countdown saat halaman dimuat
                setTimeout(countdown, 1000);
            </script>
        </div>
    </div>
</body>

</html>