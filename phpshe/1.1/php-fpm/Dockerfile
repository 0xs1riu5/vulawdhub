FROM php:5.6-apache

MAINTAINER s1riu5<s1r1u5@icloud.com>



COPY src/ /var/www/html


RUN set -x \ 
    && chmod -R 777 /var/www/html \
    && a2enmod rewrite \
    && docker-php-ext-install mysql mysqli 
