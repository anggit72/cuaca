FROM nyanpass/php5.5
EXPOSE 8080
COPY api.php .
COPY index.php .
COPY config.php .
WORKDIR .
