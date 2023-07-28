# Use the official PHP image as the base image
FROM php:8.2-apache

# Add or modify the DirectoryIndex directive to include index.php
RUN echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy your PHP application files to the container
COPY . /var/www/html/

# Install any PHP extensions or dependencies required by your app (e.g., pdo, mysqli, etc.)
RUN docker-php-ext-install pdo pdo_mysql

# Update Apache configuration to set the document root to the "public" folder
RUN sed -i 's/DocumentRoot\ \/var\/www\/html/DocumentRoot\ \/var\/www\/html\/public/g' /etc/apache2/sites-available/000-default.conf

# Expose port 80 to the host
EXPOSE 80