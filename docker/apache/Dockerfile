FROM httpd:2.4-alpine
#COPY ./ /var/www/localhost/htdocs/
RUN mkdir -p /var/www/localhost/htdocs/

ENV TZ="Europe/Kyiv"

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apk add --no-cache bash openssl curl

COPY ./docker/apache/conf/ /usr/local/apache2/conf/

RUN apk --no-cache add shadow \
 && groupmod -o -g 1001 www-data \
 && usermod -o -u 1000 -g www-data www-data

USER www-data

WORKDIR /var/www/localhost/htdocs

ENV APACHE_LOG_DIR=/usr/local/apache2/logs

EXPOSE 80 443

CMD ["httpd-foreground"]
