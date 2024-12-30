FROM httpd:2.4-alpine
COPY ./app/ /usr/local/apache2/htdocs/app/

ENV TZ="Europe/Kyiv"

# Налаштування часової зони
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Встановлення необхідних утиліт
RUN apk add --no-cache bash openssl curl

# Копіюємо власні конфігурації Apache
COPY ./docker/apache/conf/ /usr/local/apache2/conf/

RUN apk --no-cache add shadow \
 && groupmod -o -g 1001 www-data \
 && usermod -o -u 1000 -g www-data www-data

USER www-data

WORKDIR /usr/local/apache2/htdocs/app

ENV APACHE_LOG_DIR=/usr/local/apache2/logs

EXPOSE 80 443

CMD ["httpd-foreground"]
