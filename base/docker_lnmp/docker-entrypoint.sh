#!/bin/bash
set -e
    /usr/local/php/sbin/php-fpm
    /opt/mysql/bin/mysqld_safe   --user=awd --secure-file-priv='' --general-log-file="/opt/mysql/mysql-sql.log" &
    sleep 5s
    for f in /docker-entrypoint-initdb.d/*; do
            case "$f" in
                    *.sh)  echo "[Entrypoint] running $f"; . "$f" ;;
                    *.sql) echo "[Entrypoint] running $f"; mysql < "$f" && echo ;;
                    *)     echo "[Entrypoint] ignoring $f" ;;
            esac
            echo
    done

   echo "GRANT ALL ON *.* TO root@'%' IDENTIFIED BY 'shadow' WITH GRANT OPTION;Delete FROM mysql.user Where User='root' and Host='localhost'; FLUSH PRIVILEGES" | mysql

    /usr/local/nginx/sbin/nginx -g "daemon off;" -c /usr/local/nginx/conf/nginx.conf
