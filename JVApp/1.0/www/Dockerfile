FROM tomcat:8-alpine

COPY /app /tmp/app

RUN cd /usr/local/tomcat/webapps/ \
    && rm -rf * \
    && cp -r /tmp/app /usr/local/tomcat/webapps/ROOT


ENV LC_ALL zh_CN.UTF-8
ENV LANG zh_CN.UTF-8
ENV LANGUAGE zh_CN.UTF-8


