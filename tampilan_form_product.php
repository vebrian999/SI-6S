<?php
session_start();
require_once 'koneksi.php';

// Periksa apakah pengguna telah login atau belum
if (!isset($_SESSION['username'])) {
  // Jika belum login, redirect ke halaman login
  header('Location: login.php');
  exit;
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body>
  <!-- awal header -->
  <header>
    <?php
    require_once 'header.php';
    ?>
  </header>
  <!-- akhir header -->

  <!-- awal main -->
  <main>
    <div id="content" class="content">
      <article class="md:mt-32 mt-28 md:mx-16 mx-5">
        <div class="font-medium md:text-5xl text-3xl my-10 text-sky-400 text-center">
          <h1>Fill in the following data below correctly</h1>
        </div>

        <div class=" gap-x-5">
          <form action="aksi_form_product.php" method="POST" id="form_submit" class="md:flex gap-x-5" style="flex-basis: 100%" enctype="multipart/form-data">

            <div action="aksi_form_product.php" method="POST" id="form_submit" enctype="multipart/form-data" class="" style="flex-basis: 40%;">
              <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-sky-100">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                  <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                  </svg>
                  <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                  <p class="text-xs text-gray-500">SVG, PNG, JPG or JPEG (MAX. 4 PHOTO)</p>
                </div>
                <input id="dropzone-file" type="file" name="gambar[]" class="hidden" onchange="previewImages(event)" accept="image/*" multiple />
              </label>
              <div id="image-preview" class="mt-4 grid grid-cols-2 gap-4"></div>
            </div>


            <div class="bg-white px-3 py-3 shadow-2xl " style="flex-basis: 60%;">
              <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                  <div class="flex flex-col gap-x-6 gap-y-3">

                    <div class="sm:col-span-4">
                      <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                      <div class="mt-2">
                        <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                          <input type="text" name="username" id="username" autocomplete="username" class="block flex-1 border-0 bg-transparent py-4 px-2.5 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="Contoh: Deden Inoen" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" />
                        </div>
                      </div>
                    </div>


                    <div class="sm:col-span-4">
                      <label for="nbarang" class="block text-sm font-medium leading-6 text-gray-900">Nama barang</label>
                      <div class="mt-2">
                        <input id="nbarang" name="nbarang" type="nbarang" autocomplete="nbarang" placeholder="Beri tahu nama barang anda contoh: sepatu" class="px-2.5 block w-full rounded-md border-0 py-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                      </div>
                    </div>

                    <!-- Harga Barang -->
                    <div class="sm:col-span-4">
                      <label for="harga" class="block text-sm font-medium leading-6 text-gray-900">Harga Barang (Rp)</label>
                      <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                          <span class="text-black sm:text-sm">Rp</span>
                        </div>
                        <input id="harga" name="harga" type="text" autocomplete="harga" class="block w-full rounded-md border-gray-300 pl-10 pr-3 py-4 text-gray-900 placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Isi dengan angka contoh: Rp100.000" oninput="formatRupiah(this)" />
                      </div>
                    </div>
                    <script>
                      function formatRupiah(input) {
                        // Menghilangkan semua karakter kecuali angka
                        var value = input.value.replace(/\D/g, "");

                        // Menambahkan titik setiap tiga angka dari belakang
                        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

                        // Menampilkan hasil format ke input
                        input.value = value;
                      }
                    </script>

                    <!-- akhir Harga Barang -->

                    <!-- Nomor WhatsApp/Telepon -->
                    <div class="sm:col-span-4">
                      <label for="whatsapp" class="block text-sm font-medium leading-6 text-gray-900">Nomor WhatsApp/Telepon</label>
                      <div class="mt-2">
                        <input id="whatsapp" name="whatsapp" type="text" autocomplete="whatsapp" class="block w-full rounded-md border-0 py-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Contoh: 081234567890" pattern="[0-9]*" inputmode="numeric" onkeypress="return event.charCode >= 48 && event.charCode <= 57" />
                      </div>
                    </div>

                    <!-- awal input kondisi -->
                    <div class="sm:col-span-4">
                      <h3 class="mb-5 text-sm font-medium text-black">Kondisi</h3>
                      <ul class="flex gap-x-4">
                        <div class="flex flex-col gap-y-4">
                          <li>
                            <input type="checkbox" id="product_baru" name="kondisi[]" value="Product baru" class="hidden peer" />
                            <label for="product_baru" class="flex flex-col px-5 py-2.5 text-gray-500 bg-white border-2 border-gray-200 rounded-full cursor-pointer hover:text-gray-600 peer-checked:border-sky-400 peer-checked:text-sky-400 hover:bg-gray-50">
                              <div class="block">
                                <div class="w-full text-sm font-medium">Product baru</div>
                              </div>
                            </label>
                          </li>
                          <li>
                            <input type="checkbox" id="bekas_seperti_baru" name="kondisi[]" value="Bekas seperti baru" class="hidden peer" />
                            <label for="bekas_seperti_baru" class="flex px-5 py-2.5 text-gray-500 bg-white border-2 border-gray-200 rounded-full cursor-pointer hover:text-gray-600 peer-checked:border-sky-400 peer-checked:text-sky-400 hover:bg-gray-50">
                              <div class="block">
                                <div class="w-full text-sm font-medium">Bekas seperti baru</div>
                              </div>
                            </label>
                          </li>
                          <li>
                            <input type="checkbox" id="t_terlalu_sering" name="kondisi[]" value="Tidak terlalu sering digunakan" class="hidden peer" />
                            <label for="t_terlalu_sering" class="flex px-5 py-2.5 text-gray-500 bg-white border-2 border-gray-200 rounded-full cursor-pointer hover:text-gray-600 peer-checked:border-sky-400 peer-checked:text-sky-400 hover:bg-gray-50">
                              <div class="block">
                                <div class="w-full text-sm font-medium">Tidak terlalu sering digunakan</div>
                              </div>
                            </label>
                          </li>
                        </div>
                        <!-- Penambahan opsi kondisi -->
                        <div class="flex flex-col gap-y-4">
                          <li>
                            <input type="checkbox" id="lepet_dikit" name="kondisi[]" value="Lecet dikit tak ngaruh" class="hidden peer" />
                            <label for="lepet_dikit" class="flex px-5 py-2.5 text-gray-500 bg-white border-2 border-gray-200 rounded-full cursor-pointer hover:text-gray-600 peer-checked:border-sky-400 peer-checked:text-sky-400 hover:bg-gray-50">
                              <div class="block">
                                <div class="w-full text-sm font-medium">Lecet dikit tak ngaruh</div>
                              </div>
                            </label>
                          </li>
                          <li>
                            <input type="checkbox" id="retak_dikit" name="kondisi[]" value="Retak dikit" class="hidden peer" />
                            <label for="retak_dikit" class="flex px-5 py-2.5 text-gray-500 bg-white border-2 border-gray-200 rounded-full cursor-pointer hover:text-gray-600 peer-checked:border-sky-400 peer-checked:text-sky-400 hover:bg-gray-50">
                              <div class="block">
                                <div class="w-full text-sm font-medium">Retak dikit</div>
                              </div>
                            </label>
                          </li>
                          <li>
                            <input type="checkbox" id="hancur_parah" name="kondisi[]" value="Hancur parah seperti bangkai" class="hidden peer" />
                            <label for="hancur_parah" class="flex px-5 py-2.5 text-gray-500 bg-white border-2 border-gray-200 rounded-full cursor-pointer hover:text-gray-600 peer-checked:border-sky-400 peer-checked:text-sky-400 hover:bg-gray-50">
                              <div class="block">
                                <div class="w-full text-sm font-medium">Hancur parah seperti bangkai</div>
                              </div>
                            </label>
                          </li>
                        </div>
                        <!-- Akhir penambahan opsi kondisi -->
                      </ul>
                    </div>

                    <!-- akhir input kondisi -->


                    <!-- awal input lokasi barang  -->
                    <div class="sm:col-span-4">
                      <label for="lokasi" class="block text-sm font-medium leading-6 text-gray-900">Lokasi Barang</label>
                      <div class="mt-2">
                        <input id="lokasi" name="lokasi" type="text" autocomplete="lokasi" placeholder="Misal: Jakarta Selatan" class="block w-full px-3 py-2.5 rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                      </div>
                    </div>
                    <!-- akhir input lokasi barang  -->

                    <!-- kategori -->

                    <div class="w-full flex flex-col my-4   ">
                      <label class="font-base my-2 md:text-base text-sm font-light  " for="kategori"><b>Kategori</b></label>
                      <select name="kategori" id="kategori" class="border px-4 py-2 rounded-md  ring-gray-300">
                        <option value="elektronik">Elektronik</option>
                        <option value="otomotif">Otomotif</option>
                        <option value="prabot">Prabot</option>
                        <option value="fashion">Fashion</option>
                      </select>
                    </div>

                    <!-- Deskripsi Barang -->
                    <div class="sm:col-span-4">
                      <label for="deskripsi" class="block text-sm font-medium leading-6 text-gray-900">Deskripsi Barang</label>
                      <div class="mt-2">
                        <textarea id="deskripsi" name="deskripsi" rows="5" class="block w-full px-3 rounded-md border-0 py-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Beri tahu pembeli mengapa kamu menjual barang ini dan detail/kondisi barang tersebut yang mungkin membuat mereka tertarik. Pembeli suka cerita yang menarik!"></textarea>
                      </div>
                    </div>
                    <div class="sm:col-span-4">
                      <h3 class="mb-5 text-sm font-medium text-black">Persetujuan:</h3>
                      <div class="flex items-center mb-5">
                        <input type="checkbox" id="agreement" class="mr-3" />
                        <label for="agreement" class="text-gray-700 text-sm">Saya menyetujui syarat dan ketentuan.</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="index.html" type="button" class="text-sm font-semibold hover:text-white text-white bg-red-600 hover:bg-red-700 leading-6 rounded-md px-3 py-2">Cancel</a>
                <button type="submit" class="rounded-md bg-green-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                  Submit & Publish
                </button>
              </div>
            </div>
          </form>
        </div>
      </article>
    </div>
  </main>
  <!-- akhir  main -->

  <!-- awal foooter -->

  <!-- akhir footer -->

  <!-- cdn flowbite -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

  <script>
    let uploadedImages = []; // Array to store uploaded images

    function previewImages(event) {
      const files = event.target.files;
      const previewContainer = document.getElementById("image-preview");
      const maxFiles = 4; // Maximum number of files allowed
      for (let i = 0; i < Math.min(files.length, maxFiles); i++) {
        const reader = new FileReader();
        const file = files[i];
        reader.onload = function() {
          const image = new Image();
          image.src = reader.result;
          image.className = "max-w-full h-auto";
          const previewDiv = document.createElement("div");
          previewDiv.classList.add("relative");
          previewDiv.appendChild(image);
          const closeButton = document.createElement("button");
          closeButton.textContent = "Remove";
          closeButton.className = "absolute top-0 right-0 px-2 py-1 text-white bg-red-500 rounded";
          closeButton.addEventListener("click", function() {
            const index = uploadedImages.indexOf(file);
            if (index !== -1) {
              uploadedImages.splice(index, 1); // Remove image from uploadedImages array
            }
            previewDiv.remove(); // Remove preview when Remove button is clicked
          });
          previewDiv.appendChild(closeButton);
          previewContainer.appendChild(previewDiv);
          uploadedImages.push(file); // Add image to uploadedImages array
        };
        reader.readAsDataURL(file);
      }
    }
  </script>
</body>

</html>