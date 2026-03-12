FROM php:8.2-apache

# Eliminar MPMs conflictivos directamente (más confiable que a2dismod)
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
          /etc/apache2/mods-enabled/mpm_event.conf \
          /etc/apache2/mods-enabled/mpm_worker.load \
          /etc/apache2/mods-enabled/mpm_worker.conf

# Asegurar que solo mpm_prefork esté activo
RUN a2enmod mpm_prefork rewrite

# Instalar extensión mysqli
RUN docker-php-ext-install mysqli

# Permitir .htaccess en el directorio raíz
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copiar código al directorio web de Apache
COPY . /var/www/html/

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
