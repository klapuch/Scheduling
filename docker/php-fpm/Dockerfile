FROM php:7.3-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk update \
    && apk add --no-cache git postgresql-dev zip unzip make autoconf g++ zlib-dev libzip-dev bash \
    && docker-php-ext-install pdo_pgsql zip \
    && pecl install xdebug-beta \
    && curl -sS https://getcomposer.org/installer | php \
        && mv composer.phar /usr/local/bin/composer \
&& rm -rf /var/cache/apk/*