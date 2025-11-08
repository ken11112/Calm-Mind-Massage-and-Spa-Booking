#!/bin/bash
set -e

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Wait for database
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database to be ready..."
    while ! nc -z $DB_HOST ${DB_PORT:-3306}; do
      sleep 1
    done
fi

# Run migrations if enabled
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

# Laravel caching in production
if [ "$APP_ENV" = "production" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Create storage link if it doesn't exist
if [ ! -L "/var/www/public/storage" ]; then
    php artisan storage:link
fi

# Start PHP-FPM and Nginx
service nginx start
exec "$@"