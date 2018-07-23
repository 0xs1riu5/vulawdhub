FROM python:2.7-alpine3.8


RUN pip install supervisor  beautifulsoup4==4.6.0 bleach==2.1.3 blinker==1.4 bs4==0.0.1 certifi==2017.11.5 chardet==3.0.4 click==6.7 dominate==2.3.1 Flask==0.12.2 Flask-Bootstrap==3.3.7.1 Flask-HTTPAuth==3.2.4 Flask-Login==0.4.1 Flask-Mail==0.9.1 Flask-Migrate==2.2.1 Flask-Moment==0.6.0 Flask-PageDown==0.2.2 Flask-Script==2.0.6 Flask-SQLAlchemy==2.3.2 Flask-WTF==0.14.2 future==0.16.0 futures==3.2.0  html5lib==1.0.1 httpcap==0.7.9 httplib2==0.11.3 idna==2.6 itsdangerous==0.24 Jinja2==2.10 Mako==1.0.7 Markdown==2.6.11 MarkupSafe==1.0 nose==1.3.7   requests==2.18.4 six==1.11.0 SQLAlchemy==1.2.3 termcolor==1.1.0 tornado==4.5.3 visitor==0.1.3 webencodings==0.5.1 Werkzeug==0.13 WTForms==2.1 -i https://pypi.douban.com/simple/ && mkdir /app


COPY app /app

COPY supervisord.conf /etc/supervisord.conf

ENTRYPOINT ["supervisord", "--nodaemon", "--configuration", "/etc/supervisord.conf"]


