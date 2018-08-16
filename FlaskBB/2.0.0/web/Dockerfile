FROM python:2.7.15-alpine 

MAINTAINER s1riu5 <s1riu5@icloud.com>

COPY app /app

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories \
    && apk add zlib-dev jpeg-dev gcc  linux-headers musl-dev tzdata \
    && cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && cd /app  \
    && pip install -r requirements.txt -i https://pypi.tuna.tsinghua.edu.cn/simple/ \
    && pip install supervisor

WORKDIR /app

COPY supervisord.conf /etc/supervisord.conf

ENTRYPOINT ["supervisord", "--nodaemon", "--configuration", "/etc/supervisord.conf"]
