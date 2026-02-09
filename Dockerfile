# Stage 1: Build Stage
FROM composer:latest AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs --no-scripts

# Stage 2: Final Runtime Stage
FROM php:8.2-fpm

# 1. Install system dependencies (including Nginx)
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev \
    zip unzip git curl ca-certificates gnupg nginx \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Set working directory and copy app files
WORKDIR /var/www/html
COPY . .
COPY --from=vendor /app/vendor ./vendor

# 4. Build Frontend Assets
RUN npm install && npm run build

# 5. Configure Nginx
# This copies your nginx.conf into the container
COPY nginx.conf /etc/nginx/sites-available/default

# 6. Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build

# 7. Production Optimizations
ENV APP_ENV=production
ENV APP_DEBUG=false

# 8. Updated Startup Script for Nginx & PHP-FPM
RUN echo '#!/bin/bash\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
service nginx start\n\
php-fpm' > /usr/local/bin/start.sh && \
chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]