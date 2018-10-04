FROM golang:1.11-stretch

COPY origin/go/src/ /go/src
COPY origin/go/bin/ /go/bin
COPY run.sh /run.sh


RUN set -e \
    && chmod a+x /run.sh \
    && apt-get update \
    && apt-get install vim supervisor -y \
    && chmod a+x /go/bin/bee 

CMD ["/run.sh"]

#CMD ["supervisord", "--nodaemon", "-c", "/etc/supervisor/supervisord.conf"]