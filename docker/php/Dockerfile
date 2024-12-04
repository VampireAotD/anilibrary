# syntax=docker/dockerfile:1

ARG PHP_VERSION=8.3
ARG COMPOSER_VERSION=2.8.3
ARG NODE_VERSION=22.11
ARG PNPM_VERSION=9.14.4

FROM node:${NODE_VERSION}-alpine AS node

RUN --mount=type=cache,target=/root/.npm \
    npm install -g pnpm@${PNPM_VERSION}

FROM composer:${COMPOSER_VERSION} as composer

FROM php:${PHP_VERSION}-cli-alpine

ARG GROUP_ID=1000
ARG USER_ID=1000

RUN apk add --no-cache libstdc++ libpq libzip-dev gmp-dev oniguruma-dev curl git zip unzip supervisor \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers brotli-dev pcre-dev pcre2-dev zlib-dev \
    && pecl install xdebug swoole redis uv \
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
    && docker-php-ext-enable xdebug swoole redis \
    && docker-php-source delete \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/* \
    && apk del .build-deps

RUN addgroup -g ${GROUP_ID} anilibrary \
    && adduser -u ${USER_ID} -D -S -G anilibrary anilibrary

USER anilibrary

COPY --link ./php.ini /usr/local/etc/php/php.ini
COPY --link ./supervisor/supervisord.conf /etc/supervisor/supervisord.conf

COPY --link --from=composer /usr/bin/composer /usr/local/bin/composer

COPY --link --from=node /usr/lib /usr/lib
COPY --link --from=node /usr/local/lib /usr/local/lib
COPY --link --from=node /usr/local/include /usr/local/include
COPY --link --from=node /usr/local/bin /usr/local/bin

WORKDIR /anilibrary

EXPOSE 5173
EXPOSE 8000
EXPOSE 8080

ENTRYPOINT ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
