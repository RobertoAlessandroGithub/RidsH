<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Menu Detail</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <!-- Navbar -->
  <div class="flex justify-between items-center p-4 bg-white shadow">
    <button onclick="history.back()">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>
    <div class="relative">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18l-1.5 13.5H4.5L3 3z" />
      </svg>
      <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">2</span>
    </div>
  </div>

  <!-- Main content -->
  <div class="px-4 pb-28">
    <!-- Image -->
    <div class="flex justify-center mt-6">
      <img src="/images/makanan.jpg" alt="Makanan" class="rounded-full w-40 h-40 object-cover shadow-lg">
    </div>

    <!-- Title & Price -->
    <div class="mt-6 text-center">
      <h1 class="text-2xl font-bold">Jollof Rice</h1>
      <p class="text-orange-500 text-xl font-semibold mt-1">Rp12.000</p>
    </div>

    <!-- Quantity -->
    <div class="flex items-center justify-center mt-4 gap-4">
      <button class="bg-orange-500 text-white w-8 h-8 rounded-full">-</button>
      <span class="text-lg font-semibold">1</span>
      <button class="bg-orange-500 text-white w-8 h-8 rounded-full">+</button>
    </div>

    <!-- Description -->
    <div class="mt-6">
      <h2 class="text-lg font-semibold">Description</h2>
      <p class="text-sm text-gray-600 mt-2">
        Rich and incredibly tasty rice dish, made with reduced tomatoes, bell peppers, chili peppers, onions, herbs and seasoning.
      </p>
    </div>

    <!-- Recommended sides -->
    <div class="mt-6">
      <h2 class="text-lg font-semibold mb-2">Recommended Sides</h2>
      <div class="flex gap-4 overflow-x-auto pb-2">
        <div class="min-w-[100px] bg-white rounded-lg shadow p-2 text-center">
          <img src="/images/makanan.jpg" class="rounded-md h-20 w-full object-cover" />
          <p class="text-sm mt-1">Fried Plantain</p>
          <p class="text-xs text-gray-500">Rp3.000</p>
        </div>
        <div class="min-w-[100px] bg-white rounded-lg shadow p-2 text-center">
          <img src="/images/makanan.jpg" class="rounded-md h-20 w-full object-cover" />
          <p class="text-sm mt-1">Coleslaw</p>
          <p class="text-xs text-gray-500">Rp4.000</p>
        </div>
        <div class="min-w-[100px] bg-white rounded-lg shadow p-2 text-center">
          <img src="/images/makanan.jpg" class="rounded-md h-20 w-full object-cover" />
          <p class="text-sm mt-1">Fried Chicken</p>
          <p class="text-xs text-gray-500">Rp5.000</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom Cart -->
  <div class="fixed bottom-0 left-0 right-0 bg-white border-t px-4 py-3 flex justify-between items-center shadow-md">
    <div>
      <p class="text-sm text-gray-600">Total</p>
      <p class="text-lg font-bold text-orange-500">Rp20.000</p>
    </div>
    <button class="bg-orange-500 text-white px-6 py-2 rounded-full font-semibold flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18l-1.5 13.5H4.5L3 3z" />
      </svg>
      Add to Cart
    </button>
  </div>

      <!-- Footer -->
    <footer class="bg-gray-900 text-white text-sm mt-10">
        <div class="max-w-6xl mx-auto py-10 px-4 grid md:grid-cols-4 gap-8">
            <div>
                <h4 class="text-lg font-bold mb-4">Rid's Hotel</h4>
                <p>Jalan Bahagia No. 123<br>Palembang, Indonesia</p>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">About</h4>
                <ul>
                    <li><a href="/" class="hover:underline">Home</a></li>
                    <li><a href="/rooms" class="hover:underline">Rooms</a></li>
                    <li><a href="/facilities" class="hover:underline">Facilities</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">Help</h4>
                <ul>
                    <li><a href="/contact" class="hover:underline">Contact Us</a></li>
                    <li><a href="#" class="hover:underline">Privacy Policy</a></li>
                    <li><a href="#" class="hover:underline">Terms</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">Follow Us</h4>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-purple-400">Facebook</a>
                    <a href="#" class="hover:text-purple-400">Instagram</a>
                    <a href="#" class="hover:text-purple-400">Twitter</a>
                </div>
            </div>
        </div>
        <div class="text-center py-4 border-t border-gray-700">© 2025 Rid's Hotel. All rights reserved.</div>
    </footer>
</body>
</html>
