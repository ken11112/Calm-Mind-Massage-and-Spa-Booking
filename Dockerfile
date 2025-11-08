FROM php:8.2-fpm

# Install system dependencies (including libs required by extensions)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    nodejs \
    npm \
    procps \
    netcat-openbsd \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Install Composer binary from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Create directories and set permissions early
RUN mkdir -p /var/www/storage/app/public \
    /var/www/storage/framework/sessions \
    /var/www/storage/framework/views \
    /var/www/storage/framework/cache \
    /var/www/storage/logs \
    /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www

# Copy application files
COPY . /var/www

# Install PHP dependencies (do this as root so composer can write vendor)
RUN composer install --no-scripts --no-interaction --prefer-dist --optimize-autoloader || (composer diagnose && composer --version && exit 1)

# Set correct permissions for storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install node dependencies and build frontend assets (optional, but useful)
RUN npm ci --silent || npm install --silent \
    && npm run build --silent || true

# Switch to non-root user
USER www-data

# Expose port 9000 for php-fpm
EXPOSE 9000

# Copy entrypoint and make executable
USER root
COPY docker/scripts/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
USER www-data

# Default command: run entrypoint which starts php-fpm
CMD ["/usr/local/bin/entrypoint.sh"]