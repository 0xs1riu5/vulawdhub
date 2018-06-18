FROM s1r1u5/php:5.6.10


MAINTAINER s1riu5<s1r1u5@icloud.com>


COPY default.conf /etc/nginx/conf.d/
COPY php-fpm.conf /etc/php5/php-fpm.conf
COPY src/ /app


RUN set -x \ 
    && chmod -R 777 /app 


