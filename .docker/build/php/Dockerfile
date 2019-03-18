FROM php:7.2-fpm

RUN apt-get update && apt-get install -y \
	git \
	wget \
	vim \
	imagemagick \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libmcrypt-dev \
	libpng-dev \
	zlib1g-dev \
	&& rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
	mbstring \
	zip \
	pdo \
	pdo_mysql

RUN docker-php-ext-install -j$(nproc) iconv && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install -j$(nproc) gd

RUN  pecl install mcrypt-1.0.2 && docker-php-ext-enable mcrypt

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY php.ini /usr/local/etc/php/

COPY www.conf /usr/local/etc/php-fpm.d/

COPY install-composer.sh /tmp/install-composer.sh
RUN chmod +x /tmp/install-composer.sh
RUN bash /tmp/install-composer.sh
VOLUME /root/.composer
