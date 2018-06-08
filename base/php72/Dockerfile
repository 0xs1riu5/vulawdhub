FROM alpine:3.7


ADD https://repos.php.earth/alpine/phpearth.rsa.pub /etc/apk/keys/phpearth.rsa.pub

# 更新安装源
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories

# 安装基础库
RUN set -x \
    && echo "https://repos.php.earth/alpine/v3.7" >> /etc/apk/repositories \
	&& apk update && apk add --no-cache php7.2 php7.2-fpm supervisor  nginx php7.2-gd php7.2-mysqli php7.2-mbstring && mkdir /run/nginx  && mkdir /etc/supervisor.d/  && mkdir /application && mkdir /app && chmod -R 777 /app 


COPY default.conf /etc/nginx/conf.d/
COPY super.ini  /etc/supervisor.d/

#COPY php.ini /etc/php7/conf.d/50-setting.ini
COPY www.conf /etc/php/7.2/php-fpm.d/www.conf


ENTRYPOINT supervisord -n -c /etc/supervisord.conf