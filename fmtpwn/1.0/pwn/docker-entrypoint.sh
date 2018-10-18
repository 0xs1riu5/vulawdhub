#!/bin/bash

su pwn  --shell /bin/bash -c "nohup socat TCP-LISTEN:6080,fork,reuseaddr EXEC:/pwn/pwn >/dev/null 2>&1 &"

/usr/bin/supervisord --nodaemon -c /etc/supervisor/supervisord.conf
