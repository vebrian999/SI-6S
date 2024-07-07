<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="./output.css" rel="stylesheet">
    <style>
        body {
            overflow-x: hidden;
            overflow-y: hidden;
        }

        .custom-div {
            margin-left: 70px;
            width: 1475px;
            height: 770px;
            background-color: lightblue; /* Warna latar belakang untuk tujuan demonstrasi */
        }

    </style>
</head>
<body class="bg-white">
    <div class="w-screen h-screen custom-div">

        <div class="absolute top-1/2   mt-15  transform -translate-y-1/2 left-0   z-50 ">
            <p class=" text-blue text-3xl md:font-normal font-semibold md:text-7xl w-full py-2 md:mx-0  md:px-20">6second</p>
           <div class="my-4 ">
            <p class=" md:text-4xl font-normal w-full pb-4 md:px-20 ">the best deal price</p>
            <p class=" md:text-xl font-normal w-full md:px-20 ">the best place  to find quality secondhand <br>items at affordable prices!"</p>
            </div>
            <div class="md:text-xl md:px-20 py-4 flex justify-center md:ml-0 ml-20">
                <a href="login.php" class="w-full md:w-auto">
                    <button class="px-8 py-4 md:px-10 md:py-5 font-ibm bg-customBlue hover:bg-sky-400 text-white text-base md:text-xl rounded-md hover:bg-customHoverBlue cursor-pointer">
                        Explore Your Secondhand
                    </button>
                </a>
            </div>
        </div>

        <!-- kolom1 -->
        <div class="w-full h-full relative  mx-auto mobile-hiden">
            <!-- lingkaranbiru -->
            <img src="./assets/img/onboarding/Ellipse 2.png" alt="" class=" absolute top-0 right-0 w-full h-full">

            <div class="flex flex-col justify-center items-center h-screen relative left-1/4">
                <!-- gambar lingkaran penuh -->
                <div class="image-wrapper relative" style="top: 11rem; z-index: 0;">
                    <img src="./assets/img/onboarding/rotate.png" alt="" class="rounded mb-4" width="800" />
                </div>

                <div class="button flex gap-80  justify-between absolute -bottom-5 top-96 left-1/2 transform -translate-x-1/2 z-10">
                    <button class="prev-btn px-4 py-2 ">
                        <img src="./assets/img/onboarding/Group 3.png" alt="Left Arrow" class="mr-2" width="40" />
                    </button>
                    <button class="next-btn px-4 py-2 ">
                        <img src="./assets/img/onboarding/Group 3.png" alt="Left Arrow" class="mr-2" width="40" />
                    </button>
                </div>
                <!-- gambar tengah bulat -->
                <div class="image-wrapper absolute" style="z-index: 100; top: 29rem;">
                    <img id="selector-image" src="./assets/img/onboarding/5.png" alt="" class="rounded mb-4" width="200" style="z-index: 0;" />
                </div>
            </div>
            <!-- bg-putih-setengah -->
            <img src="./assets/img/onboarding/Subtract (1).png" alt="" class="  absolute top-0  left-0 w-full h-full" style="z-index: 0;" >
        </div>
    </div>
</body>

</html>

<script>
    const prevBtn = document.querySelector(".prev-btn");
    const nextBtn = document.querySelector(".next-btn");
    const img = document.querySelector(".image-wrapper img");

    let currentRotation = 0;

    prevBtn.addEventListener("click", () => {
        currentRotation -= 45;
        img.style.transform = `rotate(${currentRotation}deg)`;
    });

    nextBtn.addEventListener("click", () => {
        currentRotation += 45;
        img.style.transform = `rotate(${currentRotation}deg)`;
    });

    // Daftar gambar yang ada di database
    const images = [
        "./assets/img/onboarding/5.png",
        "./assets/img/onboarding/1.png",
        "./assets/img/onboarding/2.png",
        "./assets/img/onboarding/4.png",






        // tambahkan path gambar lainnya sesuai dengan kebutuhan
    ];

    // Inisialisasi indeks awal
    let currentIndex = 0;

    // Fungsi untuk menampilkan gambar selanjutnya
    function showNextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        updateImage();
    }

    // Fungsi untuk menampilkan gambar sebelumnya
    function showPreviousImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        updateImage();
    }

    // Fungsi untuk memperbarui gambar dengan indeks yang saat ini dipilih  
    function updateImage() {
        const image = document.getElementById("selector-image");
        image.src = images[currentIndex];
    }

    // Event listener untuk tombol kanan
    nextBtn.addEventListener("click", showNextImage);

    // Event listener untuk tombol kiri
    prevBtn.addEventListener("click", showPreviousImage);
</script>
