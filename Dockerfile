# Use the official PHP image as the base image
FROM php:8.2-apache

# Install system dependencies required by Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add or modify the DirectoryIndex directive to include index.php
RUN echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy your PHP application files to the container
COPY . /var/www/html/

# Install any PHP extensions or dependencies required by your app (e.g., pdo, mysqli, etc.)
RUN docker-php-ext-install pdo pdo_mysql
RUN composer install

WORKDIR public

# Expose port 80 to the host
EXPOSE 80