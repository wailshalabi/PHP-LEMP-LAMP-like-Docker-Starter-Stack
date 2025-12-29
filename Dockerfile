
ARG PHP_VERSION=8.2-apache-bookworm
FROM php:${PHP_VERSION}

ARG DEBIAN_FRONTEND=noninteractive

# System deps (keep it light; no dist-upgrade)
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
       ca-certificates \
       git \
       unzip \
       zip \
       libzip-dev \
       libicu-dev \
       libpng-dev \
       libjpeg62-turbo-dev \
       libfreetype6-dev \
  && rm -rf /var/lib/apt/lists/*

# PHP extensions needed for this starter (DB + intl + zip + gd)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) pdo_mysql mysqli intl zip gd

# Enable apache modules + prepare SSL dir
RUN a2enmod ssl rewrite headers \
  && mkdir -p /etc/apache2/ssl

# Install Composer (official installer)
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && php -r "unlink('composer-setup.php');"

WORKDIR /var/www/html
