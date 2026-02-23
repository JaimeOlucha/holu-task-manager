# 1. Usamos una imagen oficial de PHP con servidor Apache puro
FROM php:8.2-apache

# 2. Instalamos la extensión mysqli que necesitas para conexion.php
RUN docker-php-ext-install mysqli

# 3. Activamos la reescritura de URLs de Apache (Buena práctica)
RUN a2enmod rewrite

# 4. Copiamos todo tu código al servidor web
COPY . /var/www/html/

# 5. Damos los permisos de seguridad correctos a la carpeta
RUN chown -R www-data:www-data /var/www/html/