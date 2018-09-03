FROM php:5.6.37-apache-jessie

COPY src/ /var/www/html

RUN set -x \
 	mv /etc/apt/sources.list /etc/apt/sources.list.bak && \
    echo "deb http://mirrors.163.com/debian/ jessie main non-free contrib" >/etc/apt/sources.list && \
    echo "deb http://mirrors.163.com/debian/ jessie-proposed-updates main non-free contrib" >>/etc/apt/sources.list && \
    echo "deb-src http://mirrors.163.com/debian/ jessie main non-free contrib" >>/etc/apt/sources.list && \
    echo "deb-src http://mirrors.163.com/debian/ jessie-proposed-updates main non-free contrib" >>/etc/apt/sources.list     &&  \
	chmod -R 777 /var/www/html/ && \
    a2enmod rewrite && \
	apt-get update && \
	apt-get install libpng-dev  -y && \
	docker-php-ext-install mysql gd
