# 1. Dependency PHP
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-progress --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --no-dev --no-scripts --classmap-authoritative --ignore-platform-reqs

# 2. Build CSS & JS (Tailwind ikut memindai view di vendor, jadi vendor dibutuhkan)
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY . .
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# 3. Image akhir: Nginx + PHP-FPM
FROM php:8.4-fpm-alpine
WORKDIR /var/www/html

RUN apk add --no-cache nginx supervisor freetype libjpeg-turbo libpng libwebp libzip icu-libs \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS freetype-dev libjpeg-turbo-dev libpng-dev libwebp-dev libzip-dev icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql gd zip bcmath intl opcache \
    && apk del .build-deps

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-belfinance.ini
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

COPY --from=vendor /app/ ./
COPY --from=assets /app/public/build ./public/build

# sed: berjaga-jaga bila file ter-checkout dengan akhir baris Windows (CRLF)
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && ln -sfn ../storage/app/public public/storage \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=60s --retries=3 \
    CMD wget -q --spider http://127.0.0.1/up || exit 1

ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
