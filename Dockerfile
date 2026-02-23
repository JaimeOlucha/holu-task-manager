FROM php:8.2-apache

RUN docker-php-ext-install mysqli
RUN a2enmod rewrite

# Copiamos todo para tener las carpetas css, img, etc.
COPY . /var/www/html/

# CAMBIO CRÍTICO: Decimos a Apache que la web está en /var/www/html/src
ENV APACHE_DOCUMENT_ROOT /var/www/html/src
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN chown -R www-data:www-data /var/www/html/