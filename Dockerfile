# Stage 1: build frontend assets
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm install

COPY resources/ resources/
COPY vite.config.js ./

RUN npm run build

# Stage 2: PHP application
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

COPY --from=frontend /app/public/build /var/www/html/public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 10000

CMD php artisan config:cache && php artisan route:cache && php artisan migrate --force && php artisan storage:link --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}