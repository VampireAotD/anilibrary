ARG PHP_VERSION=8.3

FROM php:${PHP_VERSION}-fpm-alpine

ARG GROUP_ID=1000
ARG USER_ID=1000

RUN apk add --no-cache ${PHPIZE_DEPS} libzip-dev zip unzip git curl gmp-dev supervisor linux-headers \
    && pecl install xdebug \
    && docker-php-ext-install -j$(nproc) pdo_mysql zip exif pcntl bcmath gmp opcache \
    && docker-php-ext-enable xdebug \
    && addgroup -g ${GROUP_ID} anilibrary \
    && adduser -u ${USER_ID} -D -S -G anilibrary anilibrary \
    && apk del linux-headers ${PHPIZE_DEPS}

COPY ./conf.d /usr/local/etc/php/conf.d
COPY /supervisor/conf.d /etc/supervisor/conf.d
COPY /supervisor/supervisord.conf /etc/supervisor/supervisord.conf

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

USER anilibrary

WORKDIR /anilibrary