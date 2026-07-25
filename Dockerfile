FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql zip gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 10000

CMD php artisan config:cache && php artisan route:cache && php artisan migrate --force && php artisan storage:link --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}