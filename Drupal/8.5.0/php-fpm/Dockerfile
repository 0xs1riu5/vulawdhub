FROM s1r1u5/php:5.6
COPY src/ /app
COPY default.conf /etc/nginx/conf.d/default.conf

RUN apk update && apk  add php5-json  php5-pdo_mysql php5-mysqli  php5-mcrypt php5-ctype php5-dom php5-xml && chmod -R 777 /app



