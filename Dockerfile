# Frontend build stage
FROM node:18-slim AS frontend-builder

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci

# Copy config files
COPY vite.config.js postcss.config.js tailwind.config.js ./

# Copy source files
COPY resources/ ./resources/
COPY public/ ./public/

# Build frontend - output goes to public/build
RUN npm run build

# Verify build output exists
RUN test -f public/build/.vite/manifest.json || (echo "ERROR: manifest.json not found!" && ls -la public/build/ && exit 1)

# PHP application stage
FROM php:8.2-apache

# Install system dependencies including mysql client
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
    libsqlite3-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy entire application
COPY . .

# Remove old public/build if exists to prevent conflicts
RUN rm -rf public/build

# Copy built frontend assets from builder
COPY --from=frontend-builder /app/public/build ./public/build

# Verify manifest exists
RUN test -f public/build/.vite/manifest.json || (echo "ERROR: manifest.json not found!" && ls -la public/build/ && exit 1)

# Create database directory
RUN mkdir -p database && touch database/database.sqlite

# Ensure .env exists
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate app key
RUN php artisan key:generate --force

# Run migrations
RUN php artisan migrate --force

# Cache config, routes, and views
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Configure Apache
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf && \
    sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/public>|g' /etc/apache2/sites-available/000-default.conf

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache database

# Expose port
EXPOSE 80

CMD ["apache2-foreground"]
