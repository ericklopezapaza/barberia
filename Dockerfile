FROM php:8.2-fpm

# Instalar dependencias y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl nginx \
    && docker-php-ext-install pdo_mysql zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copiar archivos del proyecto
WORKDIR /var/www/html
COPY . .

# Copiar configuración de Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# Dar permisos a Laravel
RUN chmod -R 775 storage bootstrap/cache

# Exponer puerto HTTP
EXPOSE 80

# Ejecutar Nginx y PHP-FPM juntos
CMD service nginx start && php-fpm
