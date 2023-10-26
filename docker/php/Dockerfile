FROM php:8.2-fpm

COPY ./conf.d /usr/local/etc/php/conf.d

ARG GROUP_ID
ARG USER_ID

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libgmp-dev \
    supervisor \
    && pecl install xdebug \
    && docker-php-ext-install -j$(nproc) pdo_mysql zip exif pcntl bcmath gmp opcache \
    && apt-get clean  \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && groupadd -g ${GROUP_ID} anilibrary \
    && useradd -u ${USER_ID} -ms /bin/bash -g anilibrary anilibrary

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER anilibrary

WORKDIR /anilibrary