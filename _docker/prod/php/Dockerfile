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
    && pecl install redis-6.0.2\
    && docker-php-ext-enable redis gd\
    && apk del $PHPIZE_DEPS

RUN addgroup -g 1000 app && \
    adduser -u 1000 -G app -s /bin/sh -D app

RUN mkdir -p /var/www && chown -R app:app /var/www

USER app

WORKDIR /var/www
