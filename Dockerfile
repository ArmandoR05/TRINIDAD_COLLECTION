FROM php:8.2-apache

# Habilitar mod_rewrite para .htaccess
RUN a2enmod rewrite

# Instalar extensión mysqli
RUN docker-php-ext-install mysqli

# Permitir .htaccess en el directorio raíz
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copiar código al directorio web de Apache
COPY . /var/www/html/

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
