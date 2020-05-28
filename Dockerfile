FROM php:5.5-apache
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo_pgsql
ADD . /var/www/html/
