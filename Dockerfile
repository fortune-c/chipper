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
    && docker-php-ext-install \
    pdo_mysql \
    pdo_sqlite \
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

# Run composer scripts now that all files are present
RUN composer run-script post-autoload-dump

# Configure Apache to use PORT environment variable
RUN echo 'Listen ${PORT}' > /etc/apache2/ports.conf && \
    echo '<VirtualHost *:${PORT}>' > /etc/apache2/sites-available/000-default.conf && \
    echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        DirectoryIndex index.php' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port (Render uses $PORT environment variable)
ENV PORT=10000
EXPOSE 10000

# Laravel environment variables (Render will override)
ENV APP_ENV=production
ENV APP_DEBUG=false

# Start Apache
CMD ["apache2-foreground"]
