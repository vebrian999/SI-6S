<?php
require_once 'koneksi.php'; // Koneksi ke database

// Ambil data banner dari database
$sql = "SELECT * FROM banners WHERE is_active = 1";
$result = $connection->query($sql);
$banners = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $banners[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>6SECOND</title>
    <link rel="stylesheet" href="assets/app.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/your-embed-code.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
</head>
<body>
    <article id="about" class="my-28">
        <section class="relative w-full h-screen bg-blend-multiply flex items-center justify-center">
            <div id="default-carousel" class="relative w-full h-full" data-carousel="slide">
                <!-- Carousel wrapper -->
                <div class="relative h-full overflow-hidden rounded-lg mx-3 md:mx-16">
                    <?php if (!empty($banners)): ?>
                        <?php foreach ($banners as $index => $banner): ?>
                            <div class="<?php echo $index === 0 ? 'block' : 'hidden'; ?> duration-700 ease-in-out" data-carousel-item>
                                <img src="<?php echo $banner['image_path']; ?>" class="absolute block md:w-full  h-full md:object-cover top-0 left-0" alt="<?php echo $banner['alt_text']; ?>" />
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <!-- Slider indicators -->
                <div class="absolute z-30 flex -translate-x-1/2 -bottom-5 right-3 space-x-3 rtl:space-x-reverse">
                    <?php foreach ($banners as $index => $banner): ?>
                        <button type="button" class="w-3 h-3 rounded-full border-2 border-sky-300 bg-white focus:outline-none focus:ring-2 focus:ring-sky-300" aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $index + 1; ?>" data-carousel-slide-to="<?php echo $index; ?>"></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </article>

    <!-- cdn flowbite -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
