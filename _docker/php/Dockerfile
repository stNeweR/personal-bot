# FROM php:8.3-fpm-alpine
#
# COPY --from=composer:2.7 /usr/bin/composer /usr/local/bin/composer
#
# RUN apk update && apk add --no-cache \
#     postgresql-dev \
#     libpng-dev \
#     libzip-dev \
#     zip \
#     git \
#     unzip \
#     curl \
#     freetype-dev \
#     libjpeg-turbo-dev \
#     libwebp-dev \
#     jpeg-dev
#
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
# RUN docker-php-ext-install pdo_pgsql pgsql bcmath gd zip pcntl
#
# # Устанавливаем COMPOSER_HOME
# ENV COMPOSER_HOME=/tmp/composer
# RUN mkdir -p /tmp/composer && chmod 777 /tmp/composer
#
# # Создаем директорию для проекта
# WORKDIR /var/www
#
# # Даем права на запись (временное решение)
# RUN chmod 777 /var/www
#
#
# FROM php:8.3-fpm
#
# RUN apt-get update && apt-get install -y \
#       apt-utils \
#       libpq-dev \
#       libpng-dev \
#       libzip-dev \
#       git \
#       unzip \
#       && \
#       apt-get clean && \
#       rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
#
# RUN docker-php-ext-install pdo_pgsql pgsql bcmath gd zip pcntl posix
#
# RUN pecl install xdebug && docker-php-ext-enable xdebug
#
# RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#     && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#     && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#     && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#     && echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#
# # Создаем пользователя с UID 1000 (обычно первый пользователь в WSL)
# RUN adduser --disabled-password --gecos '' --uid 1000 appuser
# RUN mkdir -p /var/www && chown appuser:appuser /var/www
#
# ENV COMPOSER_ALLOW_SUPERUSER=1
# RUN curl -sS https://getcomposer.org/installer | php -- \
#     --filename=composer \
#     --install-dir=/usr/local/bin
#
# WORKDIR /var/www
#
# USER appuser
#
#
#
#

FROM php:8.3.6-fpm-alpine3.19

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --2.7.2 \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN apk --update --no-cache add  \
    $PHPIZE_DEPS \
    git \
    libpq-dev \
    linux-headers \
    freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql sockets gd exif\
    && pecl install redis-6.0.2 xdebug\
    && docker-php-ext-enable redis xdebug gd\
    && apk del $PHPIZE_DEPS

RUN addgroup -g 1000 app && \
    adduser -u 1000 -G app -s /bin/sh -D app

# Создаем рабочую директорию и устанавливаем права
RUN mkdir -p /opt/app && chown -R app:app /opt/app

USER app

WORKDIR /opt/app
