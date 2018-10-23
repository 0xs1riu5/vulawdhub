FROM ubuntu:18.04


ENV DEBIAN_FRONTEND=noninteractive

COPY nginx.conf /usr/local/nginx/conf/nginx.conf
COPY docker-entrypoint.sh /usr/local/bin/

RUN groupadd awd \
    && useradd -g awd -s "/usr/sbin/nologin" awd \
    && apt-get update \
    && apt-get install wget gnupg2  vim supervisor  tzdata build-essential libncurses5-dev libfreetype6-dev  pkg-config cmake libxml2-dev openssl libssl-dev libcurl4-gnutls-dev curl libjpeg-dev make libpng-dev libmcrypt-dev libreadline6-dev -y \
    && echo "deb http://repo.mysql.com/apt/ubuntu/ bionic mysql-5.7" > /etc/apt/sources.list.d/mysql.list \
    && apt-key adv --keyserver pgp.mit.edu --recv-keys A4A9406876FCBD3C456770C88C718D3B5072E1F5 \
    && apt-get update \
    && apt-get install -y mysql-server=5.7.24-1ubuntu18.04 \
    && echo "secure-file-priv = ''" >> /etc/mysql/mysql.conf.d/mysqld.cnf \
    && sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf \
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
    && wget http://tw2.php.net/distributions/php-7.2.11.tar.bz2 -P /tmp \
    && tar -xjf /tmp/php-7.2.11.tar.bz2 -C /tmp \
    && cd /tmp/php-7.2.11 \
    && ./configure --prefix=/usr/local/php --with-libdir=lib64 --with-config-file-path=/usr/local/php/etc --enable-fpm --with-fpm-user=awd --with-fpm-group=awd --with-mysql  --with-mysqli --with-pdo-mysql --with-iconv-dir --with-freetype-dir --with-jpeg-dir --with-png-dir --with-zlib --with-libxml-dir=/usr --enable-xml --disable-rpath --enable-bcmath --enable-shmop --enable-sysvsem --enable-inline-optimization --with-curl --enable-mbregex --enable-mbstring --with-mcrypt --enable-ftp --with-gd --enable-gd-native-ttf --with-openssl --with-mhash --enable-pcntl --enable-sockets --with-xmlrpc --enable-zip --enable-soap --without-pear --with-gettext --enable-fileinfo --enable-maintainer-zts \
    && make \
    && make install \
    && cp /usr/local/php/etc/php-fpm.conf.default /usr/local/php/etc/php-fpm.conf \
    && cp /tmp/php-7.2.11/php.ini-production /usr/local/php/etc/php.ini \
    && cp /usr/local/php/etc/php-fpm.d/www.conf.default /usr/local/php/etc/php-fpm.d/www.conf \
    && mkdir /www \
    && chown -R awd:awd /www \
    && sed -i "s/^;date.timezone =$/date.timezone = \"Asia\/Shanghai\"/" /usr/local/php/etc/php.ini \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && ln -snf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo "Asia/Shangha" > /etc/timezone \
    && cd /tmp \
    && rm -rf * \
    && chmod a+x /usr/local/bin/docker-entrypoint.sh


ENTRYPOINT ["docker-entrypoint.sh"]

