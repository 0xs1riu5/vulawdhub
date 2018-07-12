FROM mysql:5.7

MAINTAINER s1riu5 <s1riu5@icloud.com> 

ENV AUTO_RUN_DIR /docker-entrypoint-initdb.d

ENV INSTALL_DB_SQL schema.sql

COPY ./$INSTALL_DB_SQL $AUTO_RUN_DIR/

RUN echo "sql_mode=STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" >> /etc/mysql/mysql.conf.d/mysqld.cnf && chmod a+x $AUTO_RUN_DIR/$INSTALL_DB_SQL 
