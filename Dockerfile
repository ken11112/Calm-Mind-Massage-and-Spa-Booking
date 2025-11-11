# Frontend build stage
FROM node:18-slim AS frontend-builder

WORKDIR /app

# Set environment variables for the build
ENV NODE_ENV=development
ENV VITE_APP_ENV=production

# Install dependencies first (layer caching)
COPY package.json package-lock.json ./
RUN npm ci

# Copy all necessary build files
COPY vite.config.js ./
COPY postcss.config.js ./
COPY tailwind.config.js ./
COPY resources/ ./resources/

# Build frontend
RUN npm run build

# PHP application stage
FROM php:8.2-apache

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
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN apt-get update && apt-get install -y libsqlite3-dev \
    && docker-php-ext-configure pdo_sqlite --with-pdo-sqlite=/usr \
    && docker-php-ext-install pdo pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Ensure SQLite database file exists
RUN mkdir -p /var/www/html/database && touch /var/www/html/database/database.sqlite

# Ensure database directory and file have proper permissions for www-data
RUN chown -R www-data:www-data /var/www/html/database && chmod -R 775 /var/www/html/database

# Ensure .env exists
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Copy built frontend assets from builder stage
RUN mkdir -p /var/www/html/public/build
COPY --from=frontend-builder /app/public/build/ /var/www/html/public/build/

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Run Laravel setup commands
RUN php artisan key:generate --force && \
    php artisan migrate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Set Apache document root to public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

# Expose port
EXPOSE 80

CMD ["apache2-foreground"]