FROM php:fpm-alpine3.7
# docker build -t bscheshir/php:fpm-alpine-4yii2-xdebug --pull -- .
MAINTAINER s1riu5 <s1riu5@icloud.com>

# Install system packages & PHP extensions required for Yii 2.0 Framework
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories &&  \
    apk --update add \
        git \
        curl \
        curl-dev \
        bash \
        bash-completion \
        freetype-dev \
        icu \
        icu-dev \
        libxml2-dev \
        libintl \
        libjpeg-turbo-dev \
        libpng-dev \
        mysql-client \
        nodejs \
        postgresql-dev && \
    docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
        --with-png-dir=/usr/include/ && \
    docker-php-ext-configure bcmath && \
    docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install \
        soap \
        zip \
        curl \
        bcmath \
        exif \
        gd \
        iconv \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        pgsql && \
    apk del \
        icu-dev \
        gcc \
        g++ && \
    apk add --no-cache tzdata && \
    set -ex && \
# memcache
    apk add --no-cache --virtual .memcached-deps zlib-dev libmemcached-dev cyrus-sasl-dev && \
    docker-php-source extract && \
    git clone --branch php7 https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached/ && \
    docker-php-ext-install memcached && \
#    docker-php-source delete && \
    apk add --no-cache --virtual .memcached-runtime-deps libmemcached-libs && \
    apk del .memcached-deps && \
# imagick
    apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS imagemagick-dev libtool && \
    export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" && \
    pecl install imagick-3.4.3 && \
    docker-php-ext-enable imagick && \
    apk add --no-cache --virtual .imagick-runtime-deps imagemagick && \
#    apk del .phpize-deps && \
# apcu
#    apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS && \
#    docker-php-source extract && \
    pecl install apcu && \
    docker-php-ext-enable apcu && \
    docker-php-source delete && \
    apk del .phpize-deps && \
    rm -r /tmp/pear/* && \
    echo -e "expose_php = off\n\
    apc.enable_cli = 1\n\
    cgi.fix_pathinfo = 0" >> /usr/local/etc/php/php.ini

RUN ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime && \
    echo "Asia/Shanghai" > /etc/timezone && \
    echo "date.timezone = \"Asia/Shanghai\"" > /usr/local/etc/php/conf.d/timezone.ini && \
    echo "session.save_path = \"/tmp\"" > /usr/local/etc/php/conf.d/sess.ini && \
    chmod -R 777 /var/www/html/ 

