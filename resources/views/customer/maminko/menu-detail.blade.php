@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <!-- Gambar + Nama -->
    <div class="text-center">
        <img src="{{ asset('images/jollof.jpg') }}" alt="Jollof Rice" class="rounded-xl mx-auto w-64 h-64 object-cover mb-4">
        <h2 class="text-2xl font-semibold">Jollof Rice</h2>
        <p class="text-gray-500 mt-1">₦1,200</p>
    </div>

    <!-- Deskripsi + Jumlah -->
    <div class="mt-4">
        <h3 class="text-lg font-semibold">Description</h3>
        <p class="text-gray-700 text-sm mt-1">
            Rich and incredibly tasty rice dish, made with reduced tomatoes, bell peppers, chili peppers, onions, herbs and seasoning.
        </p>

        <div class="flex items-center justify-between mt-4">
            <label class="text-sm font-semibold">Quantity:</label>
            <div class="flex items-center space-x-2">
                <button class="bg-gray-200 px-2 py-1 rounded">-</button>
                <span>1</span>
                <button class="bg-gray-200 px-2 py-1 rounded">+</button>
            </div>
        </div>
    </div>

    <!-- Recommended Sides -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Recommended Sides</h3>
        <div class="flex space-x-4 overflow-x-auto pb-2">
            <div class="min-w-[120px] bg-white rounded-lg shadow p-2">
                <img src="{{ asset('images/plantain.jpg') }}" class="h-20 w-full object-cover rounded">
                <p class="text-sm mt-1 text-center">Fried Plantain</p>
                <p class="text-xs text-center text-gray-500">₦300</p>
            </div>
            <div class="min-w-[120px] bg-white rounded-lg shadow p-2">
                <img src="{{ asset('images/coleslaw.jpg') }}" class="h-20 w-full object-cover rounded">
                <p class="text-sm mt-1 text-center">Coleslaw</p>
                <p class="text-xs text-center text-gray-500">₦800</p>
            </div>
            <div class="min-w-[120px] bg-white rounded-lg shadow p-2">
                <img src="{{ asset('images/chicken.jpg') }}" class="h-20 w-full object-cover rounded">
                <p class="text-sm mt-1 text-center">Fried Chicken</p>
                <p class="text-xs text-center text-gray-500">₦900</p>
            </div>
        </div>
    </div>

    <!-- Ulasan Pembeli -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Customer Reviews</h3>
        <div class="space-y-4">
            <div class="bg-gray-100 p-3 rounded">
                <p class="text-sm font-medium">Ayo</p>
                <p class="text-xs text-gray-600">"Tasty and fresh! Loved it."</p>
            </div>
            <div class="bg-gray-100 p-3 rounded">
                <p class="text-sm font-medium">Tina</p>
                <p class="text-xs text-gray-600">"Best rice dish I’ve had in a while!"</p>
            </div>
        </div>
    </div>

    <!-- Total & Add to Cart -->
    <div class="mt-6 border-t pt-4 flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Total:</p>
            <p class="text-xl font-bold">₦2,000</p>
        </div>
        <button class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700">
            Add to Cart
        </button>
    </div>
</div>
@endsection
