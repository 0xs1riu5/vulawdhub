FROM s1riu5/lnmp:5.6 

COPY www /www
COPY destoon.sql /docker-entrypoint-initdb.d/

RUN chmod -R 777 /www 

