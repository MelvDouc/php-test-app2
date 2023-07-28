# Use an official PHP Apache image as the base image
FROM php:apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the entire PHP app into the container
COPY . /var/www/html