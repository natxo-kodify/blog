FROM nginx:1.9

MAINTAINER Daniel Javier Martín Torres <dani@yecladeyeltes.net>

ADD nginx.conf /etc/nginx/
ADD symfony.conf /etc/nginx/sites-available/

RUN mkdir /etc/nginx/sites-enabled
RUN ln -s /etc/nginx/sites-available/symfony.conf /etc/nginx/sites-enabled/symfony

RUN usermod -u 1000 www-data

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
