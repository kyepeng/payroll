# Use the official PHP image with Apache
FROM php:7.4-apache

# Install system dependencies and PHP extensions needed by Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory in the container
WORKDIR /var/www/html

# Copy existing application directory contents to the container
COPY . /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install all PHP dependencies
RUN composer install --no-interaction

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 to access the Apache server
EXPOSE 80
