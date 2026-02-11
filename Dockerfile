# Use official PHP image with Apache
FROM php:8.4-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    libicu-dev \
    libssl-dev \
    sqlite3 \
    libsqlite3-dev \
    libpq-dev \
    nodejs \
    npm \
    && docker-php-ext-install \
    pdo_mysql \
    pdo_sqlite \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    zip \
    intl \
    opcache \
    sockets \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-scripts

# Copy rest of project files
COPY . .

# Install npm dependencies and build assets
RUN npm install && npm run build

# Run composer scripts now that all files are present
RUN composer run-script post-autoload-dump

# Ensure storage directory exists and has permissions
RUN mkdir -p /var/www/html/storage/app/public/chips && \
    mkdir -p /var/www/html/storage/framework/views && \
    mkdir -p /var/www/html/storage/framework/cache && \
    mkdir -p /var/www/html/storage/framework/sessions && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set production environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false

# Configure Apache to use PORT environment variable and set DocumentRoot
COPY <<EOF /etc/apache2/sites-available/000-default.conf
<VirtualHost *:\${PORT}>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
        DirectoryIndex index.php
        
        # Ensure storage requests go through Laravel
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^ index.php [L]
    </Directory>
    
    # Prevent direct access to storage directory
    <Directory /var/www/html/storage>
        Require all denied
    </Directory>
</VirtualHost>
EOF

COPY <<EOF /etc/apache2/ports.conf
Listen \${PORT}
EOF

# Set permissions and create storage link (but skip migration here)
RUN php artisan storage:link

# Expose port (Render uses $PORT environment variable)
ENV PORT=10000
EXPOSE 10000

# Laravel environment variables (Render will override)
ENV APP_ENV=production
ENV APP_DEBUG=false

# Use entrypoint script to run migrations on startup
ENTRYPOINT ["docker-entrypoint.sh"]

