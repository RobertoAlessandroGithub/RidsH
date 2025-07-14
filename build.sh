#!/usr/bin/env bash
# exit on error
set -o errexit

# Perintah untuk men-setup proyek Laravel
php -v # Cek versi PHP
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

php artisan migrate --force
php artisan key:generate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache