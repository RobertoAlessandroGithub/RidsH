# Menggunakan image PHP 8.2 resmi sebagai dasar
FROM php:8.2-cli

# Menetapkan direktori kerja di dalam container
WORKDIR /var/www/html

# Menginstall dependensi sistem dan ekstensi PHP yang dibutuhkan Laravel
# zip untuk composer, dan libpq-dev untuk driver database PostgreSQL
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Menginstall Composer (alat manajemen package untuk PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Menyalin seluruh kode aplikasi Anda ke dalam container
COPY . .

# Perintah CMD di bawah ini hanya sebagai default,
# karena akan digantikan oleh Start Command di Render.
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
