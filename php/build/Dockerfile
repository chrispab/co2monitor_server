FROM php:7.2-fpm
RUN apt-get update && apt-get install -y
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN pecl channel-update pecl.php.net
# RUN pecl install  xdebug-3.0.4
RUN pecl install  xdebug-3.0.4 && docker-php-ext-enable xdebug
COPY 99-xdebug.ini /usr/local/etc/php/conf.d/