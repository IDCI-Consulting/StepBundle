FROM php:5.4-fpm

RUN apt-get update

RUN apt-get install git -y
RUN apt-get install wget -y
RUN apt-get install libmcrypt-dev -y
RUN apt-get install -y libxml2-dev
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng12-dev
RUN apt-get install -y imagemagick
RUN apt-get install -y zlib1g-dev libicu-dev g++

# extensions
RUN docker-php-ext-install mcrypt
RUN docker-php-ext-install pdo
RUN docker-php-ext-install gd
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip
RUN docker-php-ext-configure intl && docker-php-ext-install intl

# php.ini
COPY php.ini /usr/local/etc/php/

# install composer
COPY install-composer.sh /tmp/install-composer.sh
RUN chmod +x /tmp/install-composer.sh
RUN bash /tmp/install-composer.sh
VOLUME /root/.composer
