FROM s1riu5/lamp:7.2

MAINTAINER s1riu5<s1riu5@icloud.com>

COPY schema.sql /docker-entrypoint-initdb.d/


COPY src/ /var/www/html



RUN chmod -R 777 /var/www/html \
    && rm -rf /var/www/html/index.html 


