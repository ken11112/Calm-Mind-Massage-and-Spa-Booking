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
    nginx \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# Configure nginx
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
RUN rm /etc/nginx/sites-enabled/default

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install exif \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install zip \
    && docker-php-ext-install intl

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

# Install and build frontend assets
ENV NODE_VERSION=18.x
RUN curl -sL https://deb.nodesource.com/setup_${NODE_VERSION} | bash - \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g npm@latest

# Copy package files first to leverage Docker cache
COPY package*.json ./
RUN npm ci

# Now copy the rest and build
COPY resources/js ./resources/js
COPY resources/sass ./resources/sass
COPY resources/css ./resources/css
COPY vite.config.js .
RUN npm run build

# Switch to non-root user
USER www-data

# Set up supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN mkdir -p /var/log/supervisor

# Expose port 80 for nginx
EXPOSE 80

# Copy entrypoint and make executable
COPY docker/scripts/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Switch to root for supervisor (it needs to run as root to manage services)
USER root

# Start supervisor which will manage nginx and php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]