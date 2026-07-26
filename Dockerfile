# syntax=docker/dockerfile:1

# ---- Frontend assets ----
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# ---- PHP dependencies ----
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev

# ---- Runtime image ----
FROM php:8.3-fpm-bookworm

# Deliberately NOT purging the -dev packages after building extensions:
# apt's --auto-remove can cascade-remove the runtime shared libraries the
# compiled extensions link against (e.g. libpng16, libicu), which would
# silently break gd/intl/curl at container start. A slightly larger image
# is a better trade than an untestable-until-deploy runtime breakage.
RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx supervisor \
        libpng-dev libzip-dev libicu-dev libonig-dev libjpeg62-turbo-dev libwebp-dev libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql gd zip bcmath intl exif opcache mbstring curl \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=vendor /app ./
COPY --from=frontend /app/public/build ./public/build

RUN mkdir -p storage/app/public storage/framework/{cache,sessions,testing,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
