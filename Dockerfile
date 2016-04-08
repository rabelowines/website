FROM php:7.0.5-apache
MAINTAINER Matt Oddie <docker@mattoddie.com>

COPY config/php.ini /usr/local/etc/php/

WORKDIR /src
RUN ./composer.phar install
COPY . /var/www/html/