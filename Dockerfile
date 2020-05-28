FROM nyanpass/php5.5:5.5-apache
EXPOSE 8080
COPY api.php /var/www/html/
COPY index.php /var/www/html/
COPY config.php /var/www/html/
