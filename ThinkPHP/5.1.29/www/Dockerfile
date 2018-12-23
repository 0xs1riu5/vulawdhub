FROM s1riu5/lnmp:7.2

COPY www /www
COPY nginx.conf /usr/local/nginx/conf/nginx.conf
COPY thinkphp.sql /docker-entrypoint-initdb.d/

RUN chmod -R 777 /www


