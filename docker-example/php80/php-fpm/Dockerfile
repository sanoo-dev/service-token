FROM phpdockerio/php80-fpm:latest
MAINTAINER NguyenQuocDai <quocdaijr@gmail.com>

RUN apt-get update \
    && apt-get -y --no-install-recommends install libmcrypt-dev mysql-client php8.0-redis php8.0-amqp php8.0-intl \
    php8.0-mysql php8.0-pgsql php8.0-bcmath php8.0-gd php8.0-igbinary php8.0-imagick php8.0-intl php8.0-mongodb \
    php8.0-memcached php8.0-xmlrpc php8.0-xsl php8.0-yaml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN apt-get update \
    && apt-get -y --no-install-recommends install npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN apt-get update \
    && npm install -g n && n stable \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN apt-get update \
    && apt-get -y install git net-tools vim iputils-ping telnet\
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN apt-get update \
    && apt-get -y install supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && service supervisor start \
    && supervisorctl reload

RUN cp /usr/share/zoneinfo/Asia/Ho_Chi_Minh /etc/localtime

CMD ["/usr/sbin/php-fpm8.0", "-O"]

EXPOSE 9000