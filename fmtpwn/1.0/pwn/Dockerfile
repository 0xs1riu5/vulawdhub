FROM ubuntu:16.04

COPY pwn /pwn/pwn
COPY docker-entrypoint.sh /usr/local/bin/

RUN groupadd pwn \
    && useradd -g pwn -s "/usr/sbin/nologin" pwn \
    && chown -R pwn:pwn /pwn \
    && chmod a+x /pwn/pwn \
    && apt-get update \
    && apt-get install -y lib32z1 socat supervisor  -y \
    && chmod a+x /usr/local/bin/docker-entrypoint.sh


ENTRYPOINT ["docker-entrypoint.sh"]

     
