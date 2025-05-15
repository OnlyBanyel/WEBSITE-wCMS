FROM php:7.4-apache

# Install dependencies with proper MySQL 8 support
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mysqli opcache \
    && docker-php-ext-enable opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite ssl

# Create SSL directory
RUN mkdir -p /etc/ssl/aiven && \
    chown -R www-data:www-data /etc/ssl/aiven

WORKDIR /var/www/html

# Copy application files (excluding certs, handled in compose)
COPY . .

RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/error.ini && \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/error.ini && \
    echo "pdo_mysql.debug=1" >> /usr/local/etc/php/conf.d/pdo_mysql.ini

EXPOSE 80

