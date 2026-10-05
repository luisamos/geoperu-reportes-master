FROM php:8.3-apache

RUN a2enmod rewrite \
 && sed -ri 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf \
 && sed -ri 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

COPY public /var/www/public
COPY src /var/www/src

ENV CENSO_BASE_URL=https://reportes.geoperu.gob.pe/censos \
    CENSO_YEAR=2025 \
    REDIRECT_CODE=302

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=3s CMD curl -fs http://localhost/health || exit 1
