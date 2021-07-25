FROM nginx:stable-alpine

ADD ./docker/nginx/nginx.conf /etc/nginx/
ADD ./docker/nginx/default.conf /etc/nginx/conf.d/

RUN mkdir -p /var/www/html

RUN addgroup -g 1000 webdev && adduser -G webdev -g webdev -s /bin/sh -D webdev

RUN chown -R webdev:webdev /var/www/html

