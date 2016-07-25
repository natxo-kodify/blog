FROM mysql:5.6

MAINTAINER Daniel Javier Martín Torres <dani@yecladeyeltes.net>

ADD collation-server.cnf /etc/mysql/conf.d/

# Enable write permissions to mysql user
# https://github.com/boot2docker/boot2docker/issues/581#issuecomment-114804894
RUN usermod -u 1000 mysql
RUN usermod -G staff mysql
RUN chown mysql /var/run/mysqld