# Use the official PHP image as the base image
FROM php:8.2-apache

# Install dependencies required by Composer
RUN apt-get update && apt-get install -y git unzip

# Install Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add or modify the DirectoryIndex directive to include index.php
RUN echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy your PHP application files to the container
COPY . /var/www/html/

# Install any PHP extensions or dependencies required by your app (e.g., pdo, mysqli, etc.)
RUN docker-php-ext-install pdo pdo_mysql

# Run Composer to install the application dependencies
RUN composer install --no-dev --no-interaction

# Update Apache configuration to set the document root to the "public" folder
RUN sed -i 's/DocumentRoot\ \/var\/www\/html/DocumentRoot\ \/var\/www\/html\/public/g' /etc/apache2/sites-available/000-default.conf

# Expose port 80 to the host
EXPOSE 80