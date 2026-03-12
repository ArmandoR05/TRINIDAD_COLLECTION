FROM php:8.2-apache

# Fijar un solo MPM (evita el error "More than one MPM loaded")
RUN a2dismod mpm_event mpm_worker 2>/dev/null; a2enmod mpm_prefork

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
