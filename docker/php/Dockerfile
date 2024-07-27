# syntax=docker/dockerfile:1

ARG PHP_VERSION=8.3
ARG COMPOSER_VERSION=2.7.7
ARG NODE_VERSION=20.14
ARG PNPM_VERSION=9.3.0

FROM node:${NODE_VERSION}-alpine AS node

RUN --mount=type=cache,target=/root/.npm \
    npm install -g pnpm@${PNPM_VERSION}

FROM composer:${COMPOSER_VERSION} as composer

FROM php:${PHP_VERSION}-cli-alpine

ARG GROUP_ID=1000
ARG USER_ID=1000

RUN apk update \
    && apk add --no-cache libstdc++ libpq libzip-dev gmp-dev oniguruma-dev curl git zip unzip supervisor \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers brotli-dev pcre-dev pcre2-dev zlib-dev \
    && pecl install xdebug swoole uv \
    && docker-php-ext-install -j$(nproc) \
    pcntl \
    mbstring \
    bcmath \
    sockets \
    opcache \
    exif \
    pdo_mysql \
    zip \
    gmp \
    && docker-php-ext-enable xdebug swoole \
    && docker-php-source delete \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/* \
    && apk del .build-deps

RUN addgroup -g ${GROUP_ID} anilibrary \
    && adduser -u ${USER_ID} -D -S -G anilibrary anilibrary

USER anilibrary

COPY ./php.ini /usr/local/etc/php/php.ini
COPY ./supervisor/supervisord.conf /etc/supervisor/supervisord.conf

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

COPY --from=node /usr/lib /usr/lib
COPY --from=node /usr/local/lib /usr/local/lib
COPY --from=node /usr/local/include /usr/local/include
COPY --from=node /usr/local/bin /usr/local/bin

WORKDIR /anilibrary

EXPOSE 5173
EXPOSE 8000
EXPOSE 8080

ENTRYPOINT ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
