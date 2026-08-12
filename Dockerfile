FROM ghcr.io/geo-peru/tools/apache2-php:latest as base

FROM base as production
USER www-data
COPY --chown=www-data:www-data . /var/www
EXPOSE 8000
# Default init
CMD ["start-apache.sh"]




