FROM php:7.4-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    mariadb-client libmagickwand-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev --no-install-recommends curl bash \
    && pecl install imagick \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable imagick \
    && docker-php-ext-enable gd \
    && docker-php-ext-install pdo_mysql \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer



RUN apt-get update \
    && apt-get install -y texlive-extra-utils

RUN apt-get install -y libzip-dev

RUN docker-php-ext-install zip
