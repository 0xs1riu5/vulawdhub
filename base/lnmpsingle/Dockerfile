FROM ubuntu:16.04

COPY nginx.conf /usr/local/nginx/conf/nginx.conf
COPY docker-entrypoint.sh /usr/local/bin/

RUN groupadd awd \
    && useradd -g awd -s "/usr/sbin/nologin" awd \
    && apt-get update \
    && apt-get install wget vim supervisor  tzdata build-essential libncurses5-dev libfreetype6-dev  pkg-config cmake libxml2-dev openssl libssl-dev libcurl4-gnutls-dev curl libjpeg-dev make libpng-dev libmcrypt-dev libreadline6 libreadline6-dev -y \
    && mkdir -p /usr/local/boost /opt/mysql/data /www  \
    && wget https://cdn.mysql.com//Downloads/MySQL-5.7/mysql-boost-5.7.23.tar.gz -P /tmp \
    && tar -zxvf /tmp/mysql-boost-5.7.23.tar.gz -C /tmp \
    && cd /tmp/mysql-5.7.23 \
    && cmake -DCMAKE_INSTALL_PREFIX=/opt/mysql -DMYSQL_DATADIR=/opt/mysql/data -DDEFAULT_CHARSET=utf8 -DDEFAULT_COLLATION=utf8_general_ci -DWITH_BOOST=./boost/boost_1_59_0   \
    && make \
    && make install \
    && chown awd:awd -R /opt/mysql /www \
    && cd /opt/mysql/bin \
    && ./mysqld --initialize-insecure --user=awd --basedir=/opt/mysql --datadir=/opt/mysql/data \
    && ln -s /opt/mysql/bin/* /usr/local/bin/ \
    && echo "[mysqld]\nuser = awd\nsecure-file-priv = \"\"" > /etc/my.cnf \
    && cp ../support-files/mysql.server /etc/init.d/mysql \
    && chmod 755 /etc/init.d/mysql \
    && /etc/init.d/mysql start \
    && echo "GRANT ALL ON *.* TO root@'%' IDENTIFIED BY 'shadow' WITH GRANT OPTION;Delete FROM mysql.user Where User='root' and Host='localhost'; FLUSH PRIVILEGES" | mysql \
    && /etc/init.d/mysql stop \
    && wget ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/pcre-8.40.tar.gz -P /tmp \
    && tar -zxvf /tmp/pcre-8.40.tar.gz -C /tmp \
    && cd /tmp/pcre-8.40 \
    && ./configure \
    && make \
    && make install \
    && wget http://nginx.org/download/nginx-1.13.6.tar.gz -P /tmp \
    && tar -zxvf /tmp/nginx-1.13.6.tar.gz -C /tmp \
    && cd /tmp/nginx-1.13.6 \
    && ./configure --prefix=/usr/local/nginx --conf-path=/usr/local/nginx/conf/nginx.conf  --with-pcre=/tmp/pcre-8.40 --user=awd --group=awd \
    && make \
    && make install \
    && wget http://tw2.php.net/distributions/php-5.6.38.tar.bz2 -P /tmp \
    && tar -xjf /tmp/php-5.6.38.tar.bz2 -C /tmp \
    && cd /tmp/php-5.6.38 \
    && ./configure --prefix=/usr/local/php --with-libdir=lib64 --with-config-file-path=/usr/local/php/etc --enable-fpm --with-fpm-user=awd --with-fpm-group=awd --with-mysql  --with-mysqli --with-pdo-mysql --with-iconv-dir --with-freetype-dir --with-jpeg-dir --with-png-dir --with-zlib --with-libxml-dir=/usr --enable-xml --disable-rpath --enable-bcmath --enable-shmop --enable-sysvsem --enable-inline-optimization --with-curl --enable-mbregex --enable-mbstring --with-mcrypt --enable-ftp --with-gd --enable-gd-native-ttf --with-openssl --with-mhash --enable-pcntl --enable-sockets --with-xmlrpc --enable-zip --enable-soap --without-pear --with-gettext --disable-fileinfo --enable-maintainer-zts \
    && make \
    && make install \
    && cp /usr/local/php/etc/php-fpm.conf.default /usr/local/php/etc/php-fpm.conf \
    && cp /tmp/php-5.6.38/php.ini-production /usr/local/php/etc/php.ini \
    && sed -i "s/^;date.timezone =$/date.timezone = \"Asia\/Shanghai\"/" /usr/local/php/etc/php.ini \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && ln -snf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo "Asia/Shangha" > /etc/timezone \
    && cd /opt/mysql \
    && chmod -R 777 data \
    && cd /tmp \
    && rm -rf * \
    && chmod a+x /usr/local/bin/docker-entrypoint.sh


ENTRYPOINT ["docker-entrypoint.sh"]

