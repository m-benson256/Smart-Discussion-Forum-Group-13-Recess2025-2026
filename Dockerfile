# Stage 1: build frontend assets
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm install

COPY resources/ resources/
COPY vite.config.js ./

RUN npm run build

# Stage 2: PHP application + Python recommendation service
FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    postgresql-dev \
    python3 \
    py3-pip \
    && docker-php-ext-install pdo pdo_pgsql zip gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

COPY --from=frontend /app/public/build /var/www/html/public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Python dependencies for the recommendation service
RUN python3 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"
RUN pip install --no-cache-dir -r recommendation-service/requirements.txt

RUN chmod +x start.sh

EXPOSE 10000

CMD ["./start.sh"]