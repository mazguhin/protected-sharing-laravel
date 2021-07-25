FROM composer:2

RUN addgroup -g 1000 webdev && adduser -G webdev -g webdev -s /bin/sh -D webdev

WORKDIR /var/www/html

USER webdev
