FROM php:8.2-apache

# Instalamos mysqli
RUN docker-php-ext-install mysqli

# Activamos el módulo de reescritura
RUN a2enmod rewrite

# Copiamos todo el proyecto al servidor
COPY . /var/www/html/

# Configuramos Apache para que la carpeta raíz de la WEB sea /var/www/html/src
ENV APACHE_DOCUMENT_ROOT /var/www/html/src
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Damos permisos a todo el árbol de carpetas
RUN chown -R www-data:www-data /var/www/html/