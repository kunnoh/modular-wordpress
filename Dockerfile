FROM wordpress:php8.4-fpm-alpine AS base

WORKDIR /var/www/html/wp-content/plugins

COPY plugins.manifest.yaml /tmp/plugins.yaml
COPY docker/fetch-plugins.sh /usr/local/bin/fetch-plugins.sh
COPY wp-config.php ../../

RUN chmod +x /usr/local/bin/fetch-plugins.sh \
 && /usr/local/bin/fetch-plugins.sh

# Final image
FROM wordpress:php8.4-fpm-alpine

LABEL org.opencontainers.image.source https://github.com/kunnoh/modular-wordpress
LABEL org.opencontainers.image.description="Modular WordPress Docker image"
LABEL org.opencontainers.image.licenses="MIT"
LABEL org.opencontainers.image.version="1.0.0"

COPY --from=base /var/www/html /var/www/html
