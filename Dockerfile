FROM php:8.2-apache
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite

# Copiamos todo el proyecto
COPY . /var/www/html/

# Configuramos la raíz directamente en la nueva carpeta src con todo dentro
ENV APACHE_DOCUMENT_ROOT /var/www/html/src
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN chown -R www-data:www-data /var/www/html/