<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="p-6 bg-white shadow-md rounded-b-lg flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
        </form>
    </div>

    <div class="p-6">
        <p class="text-gray-700">Selamat datang, {{ Auth::user()->name }}!</p>
        <p class="mt-2">Kamu login sebagai <span class="font-bold text-blue-600">Admin</span>.</p>
    </div>
</body>
</html>
