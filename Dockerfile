# 1. Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# 2. Instalamos la extensión mysqli para la base de datos
RUN docker-php-ext-install mysqli

# 3. Activamos la reescritura de URLs
RUN a2enmod rewrite

# 4. CAMBIO CLAVE: Copiamos TODO el contenido de la raíz al servidor
COPY . /var/www/html/

# 5. Damos permisos de seguridad
RUN chown -R www-data:www-data /var/www/html/