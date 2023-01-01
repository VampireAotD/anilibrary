FROM php:8.1-fpm

# Configure current user
ARG GROUP_ID
ARG USER_ID

# Add current user to container
RUN groupadd -g ${GROUP_ID} user \
    && useradd -u ${USER_ID} -g user -m user \
    && usermod -p "*" user

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    locales \
    zip \
    unzip \
    git \
    curl \
    libgmp-dev \
    supervisor \
    && pecl install xdebug

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath gmp

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set user
USER user

# Set workdir
WORKDIR /anilibrary