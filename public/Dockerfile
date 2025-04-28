FROM php:8.2-apache

# Instalar dependencias del sistema antes de compilar extensiones
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copiar tu aplicación PHP
COPY . /var/www/html/

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html

# Habilitar mod_rewrite si es necesario
RUN a2enmod rewrite

# Exponer el puerto 80
EXPOSE 80

# Comando para mantener Apache en primer plano
CMD ["apache2-foreground"]
