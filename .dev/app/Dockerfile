FROM spiralscout/roadrunner:2.10.7 as rr

FROM php:8.1.9RC1-cli-alpine3.16

RUN apk update && apk add --no-cache unzip bash autoconf g++ make yaml-dev libzip-dev libpq-dev

RUN docker-php-ext-install zip sockets pdo pdo_mysql

RUN pecl install yaml-2.2.2 xdebug && docker-php-ext-enable yaml xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=rr /usr/bin/rr /usr/bin/rr
COPY --from=rr /etc/rr.yaml /etc/rr.yaml

WORKDIR /app

CMD ["/usr/bin/rr", "serve", "-d", "-c", "/etc/rr.yaml"]
