FROM httpd:2.4

# Копіюємо ваш додаток в контейнер
COPY ./app/ /usr/local/apache2/htdocs/

# Встановлення змінних оточення для часової зони
ENV TZ="Europe/Kyiv"

# Налаштування часової зони
RUN apt-get update && apt-get install -y \
    tzdata \
    bash \
    openssl \
    curl \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone \
    && apt-get clean

# Копіюємо власні конфігурації Apache
COPY ./docker/apache/conf/ /usr/local/apache2/conf/

# Перевірка чи група та користувач вже існують, і їх зміна при необхідності
RUN if ! getent group www-data; then groupadd -g 1001 www-data; fi \
    && if ! getent passwd www-data; then usermod -u 1000 -g www-data www-data; fi

# Створюємо порожні файли логів, якщо їх немає, та надаємо права доступу
RUN touch /usr/local/apache2/logs/error.log /usr/local/apache2/logs/access.log /usr/local/apache2/logs/httpd.pid && \
    chown -R www-data:www-data /usr/local/apache2/logs && \
    chmod -R 755 /usr/local/apache2/logs

# Перемикаємося на користувача www-data
USER www-data

# Встановлення робочої директорії для контейнера
WORKDIR /usr/local/apache2/htdocs

# Налаштування змінної оточення для логів Apache
ENV APACHE_LOG_DIR=/usr/local/apache2/logs

# Відкриття портів 80 і 443 для HTTP і HTTPS
EXPOSE 80 443

# Виконання Apache в передньому плані
CMD ["httpd-foreground"]