#!/bin/bash
set -e

# Create required directories
mkdir -p /var/log/supervisor
mkdir -p /var/log/nginx
mkdir -p /var/run

# Function to wait for database
wait_for_db() {
    if [ "$APP_ENV" = "local" ]; then
        # In local dev with docker-compose, wait for 'db' host
        until nc -z -v -w30 db 3306; do
            echo "Waiting for database connection (local)..."
            sleep 5
        done
    else
        # In production, try to connect to configured DB_HOST
        host="${DB_HOST:-127.0.0.1}"
        port="${DB_PORT:-3306}"
        
        echo "Checking database connection to $host:$port..."
        until php -r "
            \$host='$host';\$port='$port';
            \$tries = 0;
            while (!\$conn = @mysqli_connect(
                '$host',
                '${DB_USERNAME:-root}',
                '${DB_PASSWORD:-}',
                '${DB_DATABASE:-laravel}',
                '$port'
            )) {
                \$tries++;
                if (\$tries > 15) {
                    echo \"Unable to connect to database after \$tries tries.\n\";
                    exit(1);
                }
                sleep(1);
            }
            echo \"Database connection successful!\n\";
            mysqli_close(\$conn);
        "; do
            echo "Waiting for database connection (production)..."
            sleep 5
        done
    fi
}

# Try to connect to database
wait_for_db

# Clear and update cache
php artisan optimize:clear

if [ "$APP_ENV" != "local" ]; then
    echo "Running production optimizations..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
else
    echo "Skipping cache in local environment..."
fi

# Run migrations if database is ready
if [ "$APP_ENV" != "local" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Fix permissions
if [ "$(id -u)" = "0" ]; then
    echo "Setting storage permissions..."
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache
fi

echo "Starting PHP-FPM..."
php-fpm