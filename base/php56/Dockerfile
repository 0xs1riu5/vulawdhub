FROM alpine:3.7 



MAINTAINER s1riu5<s1r1u5@icloud.com>

ENV TIMEZONE Asia/Shanghai


RUN set -x \
    && sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories  \
    && apk add --no-cache $DEPS \
    && apk update \
    && apk add tzdata \
    && cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo "${TIMEZONE}" > /etc/timezone \
    && apk add --no-cache $DEPS  && apk add --no-cache php5 php5-fpm supervisor  php5-zlib nginx php5-gd php5-mysql && mkdir /run/nginx  && mkdir /etc/supervisor.d/   && mkdir /app && chmod -R 777 /app


COPY default.conf /etc/nginx/conf.d/
COPY super.ini  /etc/supervisor.d/

ENTRYPOINT supervisord -n -c /etc/supervisord.conf
