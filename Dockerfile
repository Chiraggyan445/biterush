FROM --platform=linux/amd64 php:8.2-cli


# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libonig-dev \
    curl \
    git \
    && docker-php-ext-install pdo_mysql zip bcmath

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set storage permissions
RUN chmod -R 775 storage bootstrap/cache

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Start Laravel app
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
