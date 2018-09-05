FROM s1r1u5/php:5.3


MAINTAINER s1riu5<s1r1u5@icloud.com>

COPY src/ /var/www/html

COPY php.ini /usr/local/lib/php.ini

RUN set -x \ 
    && chmod -R 777  /var/www/html \
    && ln -snf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo Asia/Shanghai > /etc/timezone 

