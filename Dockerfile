FROM php:5.5-apache
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql
ADD . /var/www/html/
