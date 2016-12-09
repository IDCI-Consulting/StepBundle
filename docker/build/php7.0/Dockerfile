FROM php:7.0-fpm

RUN apt-get update

RUN apt-get install git -y
RUN apt-get install wget -y
RUN apt-get install libmcrypt-dev -y
RUN apt-get install -y libxml2-dev
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng12-dev
RUN apt-get install -y libmemcached-dev
RUN apt-get install -y imagemagick
RUN apt-get install -y zlib1g-dev libicu-dev g++

# compile memcached extension
RUN docker-php-source extract
RUN cd /usr/src/php/ext && git clone -b php7 https://github.com/php-memcached-dev/php-memcached.git memcached
RUN cd /usr/src/php/ext/memcached && phpize && ./configure && make && make test && make install

# compile memcache extension
RUN cd /usr/src/php/ext && git clone -b php7 https://github.com/websupport-sk/pecl-memcache.git memcache
RUN cd /usr/src/php/ext/memcache && phpize && ./configure && make && make test && make install

# php extensions
RUN docker-php-ext-install -j$(nproc) iconv mcrypt
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install gd
RUN docker-php-ext-install xml
RUN docker-php-ext-install soap
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install opcache
RUN docker-php-ext-install zip
RUN docker-php-ext-install memcached
RUN docker-php-ext-install memcache
RUN docker-php-ext-install intl

# php.ini
COPY php.ini /usr/local/etc/php/

# install composer
COPY install-composer.sh /tmp/install-composer.sh
RUN chmod +x /tmp/install-composer.sh
RUN bash /tmp/install-composer.sh
VOLUME /root/.composer
