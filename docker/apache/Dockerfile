FROM httpd:2.4-alpine

# Встановлення необхідних утиліт
RUN apk add --no-cache bash openssl curl

# Копіюємо власні конфігурації Apache

COPY ./conf.d/ /usr/local/apache2/conf/conf.d/
COPY ./ssl/ /usr/local/apache2/conf/ssl/
COPY ./sites-enabled/ /usr/local/apache2/conf/sites-enabled/

ADD ./httpd.conf /usr/local/apache2/conf/

# Активуємо модулі Apache вручну
RUN sed -i \
    -e 's/^#LoadModule rewrite_module/LoadModule rewrite_module/' \
    -e 's/^#LoadModule proxy_module/LoadModule proxy_module/' \
    -e 's/^#LoadModule proxy_fcgi_module/LoadModule proxy_fcgi_module/' \
    -e 's/^#LoadModule ssl_module/LoadModule ssl_module/' \
    -e 's/^#LoadModule headers_module/LoadModule headers_module/' \
    /usr/local/apache2/conf/httpd.conf


# Інтегруємо додаткові конфігурації в основний конфігураційний файл
#RUN echo "Include conf/extra/000-default.conf" >> /usr/local/apache2/conf/httpd.conf && \
#    echo "Include conf/extra/default-ssl.conf" >> /usr/local/apache2/conf/httpd.conf

# Генеруємо SSL-сертифікати
#RUN mkdir -p /usr/local/apache2/conf/ssl && \
#    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
#    -keyout /usr/local/apache2/conf/ssl/apache.key \
#    -out /usr/local/apache2/conf/ssl/apache.crt \
#    -subj "/C=US/ST=Denial/L=Springfield/O=Dis/CN=localhost"
#RUN chmod 644 /usr/local/apache2/conf/ssl/apache.key


ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

USER www-data

WORKDIR /usr/local/apache2/

ENV APACHE_LOG_DIR=/var/www/logs

EXPOSE 80 443
