FROM php:7.4-fpm

MAINTAINER Homekonect <piotrgueye@gmail.com>

ARG APP_PROJECT_DIR_NAME

WORKDIR /var/www/project/${APP_PROJECT_DIR_NAME}



RUN apt-get update && apt-get install -y \
   apt-utils \
   sudo \
   wget \
   unzip \
   cron \
   curl \
   libmcrypt-dev \
   libicu-dev \
   libxml2-dev libxslt1-dev \
   libzip-dev \
   libpng-dev \
   git \
   vim \
   mariadb-client \
   ocaml \
   expect \
   && docker-php-ext-install -j$(nproc) intl xsl gd zip pdo_mysql opcache soap bcmath json iconv \
   && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# PHP config
ADD conf/php.ini /usr/local/etc/php
ADD conf/project.pool.conf /usr/local/etc/php/pool.d/

# Install composer
RUN cd /var/www/ \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/bin/composer \
    && chmod +x /usr/bin/composer

RUN usermod -u 1000 www-data
RUN chown -R www-data:www-data /var/www/

RUN echo Europe/Paris | tee /etc/timezone && dpkg-reconfigure --frontend noninteractive tzdata

ADD conf/auth.json /var/www/.composer/
RUN chown -R www-data:www-data /var/www/.composer
RUN chown -R www-data:www-data /var/www/project/${APP_PROJECT_DIR_NAME}

# Activate www-data cron
ADD crontab/www-data /var/spool/cron/crontabs/
RUN chown www-data:crontab /var/spool/cron/crontabs/www-data
RUN chmod 600 /var/spool/cron/crontabs/www-data
RUN mkdir -p /var/lib/php/session && chown -R www-data:www-data /var/lib/php/session

COPY bin/* /usr/local/bin/
RUN chmod +x /usr/local/bin/start

RUN echo "www-data:www-data" | chpasswd && adduser www-data sudo
RUN echo "www-data ALL=(root) NOPASSWD:ALL" > /etc/sudoers.d/www-data && chmod 0440 /etc/sudoers.d/www-data

USER www-data

RUN echo "alias ll='ls -l'" >> ~/.bashrc

CMD ["/usr/local/bin/start"]

EXPOSE 9000