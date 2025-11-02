# Mulai dari image PHP 8.2 Apache yang kita gunakan
FROM php:8.2-apache

# Install ekstensi (driver) yang dibutuhkan PHP untuk koneksi ke MySQL
RUN docker-php-ext-install pdo pdo_mysql