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

# Copy CA cert into container
COPY ./aiven-certs/ca.pem /etc/ssl/aiven/ca.pem
RUN chmod 600 /etc/ssl/aiven/ca.pem
