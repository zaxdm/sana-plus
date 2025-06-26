# Imagen base oficial con PHP 8.2 y Apache
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilitar mod_rewrite para .htaccess si lo usas
RUN a2enmod rewrite

# Configurar permisos y copiar todo al contenedor
COPY . /var/www/html/

# Asegurarse que los archivos pertenezcan al usuario de Apache
RUN chown -R www-data:www-data /var/www/html

# Puerto de exposición
EXPOSE 80
