FROM debian:jessie

MAINTAINER Homekonect <piotrgueye@gmail.com>

RUN apt-get update && apt-get install -y nginx nginx-extras vim

ADD nginx.conf /etc/nginx/
RUN rm /etc/nginx/sites-enabled/default

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN usermod -u 1000 www-data

RUN echo Europe/Paris | tee /etc/timezone && dpkg-reconfigure --frontend noninteractive tzdata
RUN apt-get install zip -y

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
