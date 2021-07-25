FROM php:7.4-fpm-alpine
ADD ./docker/php/www.conf /usr/local/etc/php-fpm.d/
RUN addgroup -g 1000 webdev && adduser -G webdev -g webdev -s /bin/sh -D webdev
RUN mkdir -p /var/www/html
RUN chown -R webdev:webdev /var/www/html
WORKDIR /var/www/html
RUN docker-php-ext-install pdo pdo_mysql pcntl

RUN apk add --no-cache zip libzip-dev
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip

RUN apk update && apk add --no-cache supervisor
COPY ./docker/supervisor/supervisord.conf /etc/

USER webdev
