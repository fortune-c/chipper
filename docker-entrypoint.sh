#!/bin/bash
set -e

# Clear caches to ensure new routes/config are picked up
echo "Clearing caches..."
php artisan optimize:clear

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Optimize for production
echo "Caching configuration and routes..."
php artisan optimize

# Start Apache
echo "Starting Apache..."
exec apache2-foreground
