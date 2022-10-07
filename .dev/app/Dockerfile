FROM spiralscout/roadrunner:2.10.7 as rr

FROM php:8.1.9RC1-cli-alpine3.16

RUN apk update && apk add --no-cache unzip bash autoconf g++ make yaml-dev libzip-dev libpq-dev go ssmtp

RUN docker-php-ext-install zip sockets pdo pdo_pgsql

RUN pecl install yaml-2.2.2 xdebug && docker-php-ext-enable yaml xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=rr /usr/bin/rr /usr/bin/rr
COPY --from=rr /etc/rr.yaml /etc/rr.yaml

ENV GOROOT /usr/lib/go
ENV GOPATH /go

ENV PATH /usr/local/go/bin:$PATH

RUN go install github.com/mailhog/mhsendmail@latest

RUN cp /go/bin/mhsendmail /usr/bin/mhsendmail

RUN echo 'sendmail_path = "/usr/bin/mhsendmail --smtp-addr=mailhog:1025"' > /usr/local/etc/php/php.ini

WORKDIR /api

CMD ["/usr/bin/rr", "serve", "-d", "-c", "/etc/rr.yaml"]
