#!/bin/sh

# Перевірка прав на /var/run/php-fpm
mkdir -p /var/run/php-fpm
chown -R www-data:www-data /var/run/php-fpm
chmod 0755 /var/run/php-fpm

# Запуск PHP-FPM
exec "$@"