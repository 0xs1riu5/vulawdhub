FROM s1r1u5/php:5.6 

MAINTAINER s1riu5<s1r1u5@icloud.com>


COPY default.conf /etc/nginx/conf.d/
COPY super.ini  /etc/supervisor.d/

COPY src/ /app


RUN set -x \ 
    && chmod -R 777 /app \
    && apk update \
    && apk add php5-iconv


