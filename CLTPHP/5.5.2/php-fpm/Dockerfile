FROM php:5.6-apache

COPY src/ /var/www/html/
COPY .htaccess /var/www/html/
COPY apache2.conf /etc/apache2/apache2.conf


RUN docker-php-ext-install pdo pdo_mysql && chmod -R 777 /var/www/html && ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load
