FROM tomcat:7
MAINTAINER s1riu5 <s1riu5@icloud.com>


RUN set -ex \
	&& rm -rf /usr/local/tomcat/webapps/* \
	&& chmod a+x /usr/local/tomcat/bin/*.sh

# struts-2.3.34
COPY struts2-showcase.war  /usr/local/tomcat/webapps/ROOT.war
