FROM postgres:17.2-alpine

USER root

RUN apk --no-cache add shadow \
    && groupmod -o -g 1001 postgres \
    && usermod -o -u 1000 postgres \
    && mkdir -p /var/lib/postgresql/data \
    && chown -R postgres:postgres /var/lib/postgresql

USER postgres

WORKDIR /var/lib/postgresql

ENV PGDATA=/var/lib/postgresql/data

EXPOSE 5432

CMD ["postgres"]
