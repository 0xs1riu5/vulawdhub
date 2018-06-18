FROM alpine:3.7
 
ENV TIMEZONE Asia/Shanghai


COPY php.tar.gz /root/

# install nginx
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories  \
&&  apk update  \
&&  apk --no-cache add tzdata curl supervisor  autoconf dpkg-dev dpkg file g++ gcc libc-dev make pkgconf nginx  tar  xz libxml2-dev libmcrypt-dev openssl-dev bzip2 bzip2-dev curl-dev  libpng-dev \
&& cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime  \
&&  echo "Shanghai" > /etc/timezone  \
&& mkdir /app \
&& mkdir /root/php \
&& tar -zxf /root/php.tar.gz -C "/root/php/" --strip-components=1   \
&& cd /root/php/ \
&& ./configure --prefix=/usr/local/php5 --sysconfdir=/etc/php5 --with-config-file-path=/etc/php5 --enable-fpm --with-mysql=mysqlnd --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd --with-mhash --with-openssl --with-zlib --with-bz2 --with-curl --with-libxml-dir --with-gd --with-zlib --enable-mbstring --with-mcrypt \
&& make \
&& make install \
&& mkdir -p /run/nginx \
&& cp /etc/php5/php-fpm.conf.default  /etc/php5/php-fpm.conf \
&& rm -rf /root/


COPY default.conf /etc/nginx/conf.d/

COPY super.ini /etc/supervisor.d/ 


ENTRYPOINT supervisord -n -c /etc/supervisord.conf
