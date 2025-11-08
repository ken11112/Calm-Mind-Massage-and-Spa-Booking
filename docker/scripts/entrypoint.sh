#!/bin/bash

# Wait for database to be ready
until nc -z -v -w30 db 3306
do
  echo "Waiting for database connection..."
  sleep 5
done

# Update composer dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate application key if not set
php artisan key:generate --no-interaction --force
php artisan config:clear
php artisan config:cache

# Run migrations
php artisan migrate --force

# Cache routes and config
php artisan route:cache
php artisan config:cache
php artisan view:cache

# Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Start PHP-FPM
php-fpm