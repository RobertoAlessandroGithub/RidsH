<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>
</head>
<body>
    <h1>Tambah Menu</h1>
    <form action="/menus" method="POST">
        @csrf
        <label for="name">Nama:</label>
        <input type="text" name="name" required>
        <br>
        <label for="description">Deskripsi:</label>
        <textarea name="description" required></textarea>
        <br>
        <label for="price">Harga:</label>
        <input type="number" name="price" required>
        <br>
        <label for="image">Gambar:</label>
        <input type="text" name="image">
        <br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>