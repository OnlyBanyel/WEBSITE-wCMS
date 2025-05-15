FROM php:7.4-apache

# Install required dependencies
RUN apt-get update && apt-get install -y \
    libssl-dev \
    openssl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions with SSL support
RUN docker-php-ext-install mysqli pdo pdo_mysql && \
    docker-php-ext-enable opcache

# Enable Apache modules
RUN a2enmod rewrite ssl

# Create SSL directory and set permissions
RUN mkdir -p /etc/ssl/aiven && \
    chown -R www-data:www-data /etc/ssl/aiven

WORKDIR /var/www/html

# Copy application files (excluding certs, handled in compose)
COPY . .

RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/error.ini && \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/error.ini && \
    echo "pdo_mysql.debug=1" >> /usr/local/etc/php/conf.d/pdo_mysql.ini

EXPOSE 80

