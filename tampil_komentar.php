<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>6SECOND</title>
    <link rel="stylesheet" href="assets/komentar.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/your-embed-code.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  </head>
  <body>
    <!-- awal main -->
    <main>
      <div id="content" class="content">
        <!-- awal komeng -->
        <section class="bg-white py-8 lg:py-16 antialiased">
          <div class="mx-10">
            <div class="flex justify-between items-center mb-6">
              <h2 class="font-semibold text-3xl text-left">Silahkan isi ulasan anda (20)</h2>
            </div>
            <form id="comment-form" class="mb-6">
              <div class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200">
                <label for="comment" class="sr-only">Your comment</label>
                <textarea id="comment" rows="6" class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none" placeholder="Type your comment here...." required></textarea>
              </div>
              <button type="submit" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center bg-primary-700 rounded-lg text-white focus:ring-4 focus:ring-primary-200 bg-sky-400">Post comment</button>
            </form>
            <div id="comments-container">
              <!-- Comments will be loaded here via JavaScript -->
            </div>
          </div>
        </section>
        <!-- akhir komeng -->
      </div>
    </main>
    <!-- akhir  main -->

    <!-- awal foooter -->
    <!-- akhir footer -->

    <!-- cdn flowbite -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="assets/komentar.js"></script>
  </body>
</html>
