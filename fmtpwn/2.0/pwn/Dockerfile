FROM ubuntu:18.04

COPY test /pwn/test
COPY docker-entrypoint.sh /usr/local/bin/

RUN groupadd pwn \
    && useradd -g pwn -s "/usr/sbin/nologin" pwn \
    && chown -R pwn:pwn /pwn \
    && chmod a+x /pwn/test \
    && apt-get update \
    && apt-get install -y lib32z1 socat supervisor  -y \
    && chmod a+x /usr/local/bin/docker-entrypoint.sh


ENTRYPOINT ["docker-entrypoint.sh"]

     
