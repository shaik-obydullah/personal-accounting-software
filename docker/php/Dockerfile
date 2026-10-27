FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring zip intl

RUN echo "zend_extension=opcache" > /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN groupadd -g 1000 appuser && useradd -m -u 1000 -g appuser appuser

USER appuser

WORKDIR /var/www/symfony
