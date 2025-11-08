#!/bin/bash
set -e

# Install frontend dependencies
echo "Installing npm dependencies..."
npm ci

# Build frontend assets
echo "Building frontend assets..."
npm run build

# Install PHP dependencies
echo "Installing composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Laravel setup
echo "Setting up Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link

# Set permissions
echo "Setting correct permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache