# Pakai image PHP + Apache
FROM php:8.3-apache

# Aktifkan mod_rewrite untuk .htaccess
RUN a2enmod rewrite

# Install ekstensi PHP mysqli (opsional, jika kamu pakai MySQL)
RUN docker-php-ext-install mysqli

# Copy semua file project ke folder htdocs di server
COPY . /var/www/html/

# Set permission supaya aman
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
