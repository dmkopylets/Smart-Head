# Stage 1: Build dependencies
FROM php:8.4.2-fpm-alpine AS builder
LABEL description="Alpine based image with php:8.4.2-fpm"

#COPY ./ /var/www/localhost/htdocs/
RUN mkdir -p /var/www/localhost/htdocs/

# Встановлення змінних оточення
ENV COMPOSER_MEMORY_LIMIT='-1'
ENV TZ="Europe/Kyiv"

# Налаштування часової зони
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Встановлення базових утиліт і build-залежностей
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    bash \
    build-base \
    coreutils \
    curl \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    postgresql-dev \
    icu-dev \
    zlib-dev \
    oniguruma-dev \
    linux-headers \
 && apk add --no-cache git nodejs npm

# Встановлення стандартних розширень PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
    bcmath \
    calendar \
    exif \
    gd \
    intl \
    opcache \
    pdo_pgsql \
    zip \
    sockets

RUN pecl install apcu \
 && docker-php-ext-enable apcu

RUN pecl install xdebug \
 && docker-php-ext-enable xdebug

# Додавання Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Збереження фінальної структури
RUN php -m && php --version

# Stage 2: Runtime environment
FROM php:8.4.2-fpm-alpine
LABEL description="Slimmed down PHP 8.4.2 image with necessary extensions"

ENV TZ="Europe/Kyiv"
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Додавання лише runtime-залежностей
RUN apk add --no-cache \
    freetype \
    libjpeg-turbo \
    libpng \
    libzip \
    postgresql-libs \
    icu-libs \
    zlib \
    oniguruma \
    nodejs \
    npm

# Копіювання з етапу builder
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY --from=builder /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/localhost/htdocs

RUN apk --no-cache add shadow \
 && groupmod -o -g 1001 www-data \
 && usermod -o -u 1000 -g www-data www-data

# Створення та налаштування прав для сокета
USER root
RUN mkdir -p /var/run/php-fpm && \
    chown -R www-data:www-data /var/run/php-fpm && \
    chmod 0755 /var/run/php-fpm

# Повернення до користувача www-data
USER www-data

EXPOSE 9001 9000
CMD ["php-fpm"]
