FROM httpd:2.4-alpine

# Встановлення необхідних утиліт
RUN apk add --no-cache bash openssl curl

# Активуємо модулі Apache вручну
RUN sed -i \
    -e 's/^#LoadModule rewrite_module/LoadModule rewrite_module/' \
    -e 's/^#LoadModule proxy_module/LoadModule proxy_module/' \
    -e 's/^#LoadModule proxy_fcgi_module/LoadModule proxy_fcgi_module/' \
    -e 's/^#LoadModule ssl_module/LoadModule ssl_module/' \
    -e 's/^#LoadModule headers_module/LoadModule headers_module/' \
    /usr/local/apache2/conf/httpd.conf

# Копіюємо власні конфігурації Apache
COPY conf/000-default.conf /usr/local/apache2/conf/extra/000-default.conf
COPY conf/default-ssl.conf /usr/local/apache2/conf/extra/default-ssl.conf

# Інтегруємо додаткові конфігурації в основний конфігураційний файл
RUN echo "Include conf/extra/000-default.conf" >> /usr/local/apache2/conf/httpd.conf && \
    echo "Include conf/extra/default-ssl.conf" >> /usr/local/apache2/conf/httpd.conf

# Генеруємо SSL-сертифікати
#RUN mkdir -p /usr/local/apache2/conf/ssl && \
#    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
#    -keyout /usr/local/apache2/conf/ssl/apache.key \
#    -out /usr/local/apache2/conf/ssl/apache.crt \
#    -subj "/C=US/ST=Denial/L=Springfield/O=Dis/CN=localhost"

#RUN chmod 644 /usr/local/apache2/conf/ssl/apache.key

WORKDIR /var/www/app

ENV APACHE_LOG_DIR=/var/www/logs

EXPOSE 80 443
