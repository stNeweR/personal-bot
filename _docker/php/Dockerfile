FROM php:8.3-fpm-alpine

COPY --from=composer:2.7 /usr/bin/composer /usr/local/bin/composer

RUN apk update && apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libzip-dev \
    zip \
    git \
    unzip \
    curl \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    jpeg-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install pdo_pgsql pgsql bcmath gd zip pcntl

# Опционально: xdebug только для разработки
# RUN apk add --no-cache $PHPIZE_DEPS && pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /var/www
