# Render Deployment Guide

## Environment Variables to Set on Render

Add these environment variables in your Render dashboard:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://chipper-7qnm.onrender.com
FORCE_HTTPS=true
FILESYSTEM_DISK=public

# Generate a new key with: php artisan key:generate --show
APP_KEY=base64:your-generated-key-here

# Database (if using external DB)
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

## Build Command
```bash
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache && mkdir -p storage/app/public/chips && chmod -R 755 storage && php artisan storage:link
```

## Start Command
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

## Important Notes

1. The app now automatically detects when it's behind a proxy (like Render) and forces HTTPS
2. Make sure `FILESYSTEM_DISK=public` is set for image uploads to work
3. Run migrations manually after first deployment: `php artisan migrate --force`
4. A fallback route serves storage files directly through Laravel if symlinks don't work on Render
5. Ensure storage directories have proper permissions (755) during build
