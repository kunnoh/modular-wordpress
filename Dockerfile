FROM wordpress:php8.4-fpm-alpine

WORKDIR /var/www/html/wp-content/plugins

COPY plugins.manifest.yaml /tmp/plugins.yaml && \
docker/fetch-plugins.sh /usr/local/bin/fetch-plugins.sh && \
wp-config.php ../../

RUN chmod +x /usr/local/bin/fetch-plugins.sh \
 && /usr/local/bin/fetch-plugins.sh

FROM wordpress:php8.2-apache
COPY --from=base /var/www/html /var/www/html
