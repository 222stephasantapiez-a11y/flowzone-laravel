FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libpng-dev \
    libjpeg-dev libzip-dev nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql gd zip pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN cp .env.example .env \
    && php artisan key:generate \
    && php artisan storage:link || true

RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

CMD ["/bin/sh", "-c", "php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=8000"]