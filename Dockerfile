FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip
RUN docker-php-ext-configure zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Create storage directory structure
RUN mkdir -p /var/www/storage/app/public \
    /var/www/storage/framework/{sessions,views,cache} \
    /var/www/storage/logs \
    /var/www/bootstrap/cache

# Copy existing application directory contents
COPY . /var/www

# Install dependencies
RUN composer install --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install npm dependencies and build assets
RUN npm install && npm run build

# Change current user to www-data
USER www-data

# Expose port 9000
EXPOSE 9000

# Add netcat for database connection checking
RUN apt-get update && apt-get install -y netcat-traditional && rm -rf /var/lib/apt/lists/*

# Copy and set permissions for entrypoint script
COPY docker/scripts/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# Start with entrypoint script
CMD ["/usr/local/bin/entrypoint.sh"]