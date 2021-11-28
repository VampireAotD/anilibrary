FROM php:8.0-fpm

# Configure current user
ARG GROUP_ID=1000
ENV GROUP_ID ${GROUP_ID}
ARG USER_ID=1000
ENV USER_ID ${USER_ID}

WORKDIR /var/www/anilibrary/

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6 \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g ${GROUP_ID} user \
    && useradd -u ${USER_ID} -g user -m user \
    && usermod -p "*" user