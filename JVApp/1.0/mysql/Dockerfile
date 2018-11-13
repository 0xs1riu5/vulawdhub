FROM mysql:5.7

MAINTAINER s1riu5 <s1riu5@icloud.com>

ENV AUTO_RUN_DIR /docker-entrypoint-initdb.d

ENV INSTALL_DB_SQL schema.sql

COPY ./$INSTALL_DB_SQL $AUTO_RUN_DIR/

RUN chmod a+x $AUTO_RUN_DIR/$INSTALL_DB_SQL