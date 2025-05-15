# Use the official PHP image with Apache
FROM php:7.4-apache

# Install the required PHP extensions for MySQL/MariaDB
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (if your site requires URL rewriting)
RUN a2enmod rewrite

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the local PHP project files into the container
COPY . .

# Expose the default Apache port
EXPOSE 80

FROM mariadb:11

RUN apt-get update && apt-get install -y mariadb-client && rm -rf /var/lib/apt/lists/*

