FROM s1riu5/lnmp:5.6

MAINTAINER s1riu5<s1r1u5@icloud.com>

COPY src/ /www
COPY schema.sql /docker-entrypoint-initdb.d/


RUN chmod -R 777 /www


