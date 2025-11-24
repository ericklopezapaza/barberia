# Usar PHP-FPM 8.2
FROM php:8.2-fpm

# Instalar dependencias y extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl \
    && docker-php-ext-install pdo_mysql zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar todos los archivos del proyecto
COPY . .

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# Dar permisos correctos para storage y cache
RUN chmod -R 775 storage bootstrap/cache

# Exponer puerto 80 (Render lo necesita para el Web Service)
EXPOSE 80

# Ejecutar PHP-FPM en primer plano y accesible externamente
CMD ["php-fpm", "-F", "-R"]

